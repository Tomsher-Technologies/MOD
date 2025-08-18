<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

trait HandlesUpdateConfirmation
{
    /**
     * Nicely formats display values for confirmation payload.
     */
    private function formatDisplayValue($value, $isBoolean = false)
    {
        if ($isBoolean) {
            return $value ? 'Yes' : 'No';
        }
        if ($value === null || $value === '') {
            return 'N/A';
        }
        return $value;
    }

    /**
     * Helper to get human-readable label for given ID using display_with config.
     */
    private function getDisplayLabel($modelClass, $keyField, $labelField, $id)
    {
        if ($id === null) {
            return 'N/A';
        }
        return $modelClass::where($keyField, $id)->value($labelField) ?? $id;
    }

    protected function processUpdate(
        Request $request,
        Model $model,
        array $validatedData,
        array $relationsToCompare = []
    ) {
        if ($request->has('_is_confirmed')) {
            // Already confirmed, just proceed
            return [
                'data' => $validatedData,
                'notify' => $request->input('_notify_fields', []),
            ];
        }

        $changedFields = [];
        $mainModelData = Arr::only($validatedData, $model->getFillable());
        $originalMainData = $model->getOriginal();

        // Handle normal scalar fields
        foreach ($mainModelData as $key => $newValue) {
            if (!array_key_exists($key, $originalMainData)) {
                continue;
            }

            $originalValue = $originalMainData[$key];
            $isBooleanField = $model->hasCast($key, 'boolean');

            $isChanged = $isBooleanField
                ? ((bool) $originalValue !== (bool) $newValue)
                : ($originalValue != $newValue);

            if ($isChanged) {
                // Check if there is display_with config for this field (with or without _id)
                $displayLabel = null;
                if (isset($relationsToCompare[$key]['display_with'])) {
                    $dw = $relationsToCompare[$key]['display_with'];
                    $displayOld = $this->getDisplayLabel($dw['model'], $dw['key'], $dw['label'], $originalValue);
                    $displayNew = $this->getDisplayLabel($dw['model'], $dw['key'], $dw['label'], $newValue);
                    $changedFields[$key] = [
                        'label' => Str::headline(str_replace('_id', '', $key)),
                        'old' => $this->formatDisplayValue($displayOld, $isBooleanField),
                        'new' => $this->formatDisplayValue($displayNew, $isBooleanField),
                    ];
                    continue;
                }

                $changedFields[$key] = [
                    'label' => Str::headline(str_replace('_id', '', $key)),
                    'old' => $this->formatDisplayValue($originalValue, $isBooleanField),
                    'new' => $this->formatDisplayValue($newValue, $isBooleanField),
                ];
            }
        }

        // Handle relations
        foreach ($relationsToCompare as $requestKey => $config) {
            if (!isset($validatedData[$requestKey])) {
                continue;
            }
            $relationName = $config['relation'] ?? null;

            // Special handling for list/checkbox relations (sync tables)
            if (($config['type'] ?? null) === 'list' && isset($config['column'])) {
                $oldList = $model
                    ->{$relationName}()
                    ->pluck($config['column'])
                    ->sort()
                    ->values();

                $newList = collect($validatedData[$requestKey])
                    ->map(fn($v) => (int) $v)
                    ->sort()
                    ->values();

                $removed = $oldList->diff($newList);
                $added = $newList->diff($oldList);

                if ($removed->isNotEmpty() || $added->isNotEmpty()) {
                    $mapFn = fn($id) => $id;  // default: ids

                    if (isset($config['display_with'])) {
                        $dw = $config['display_with'];
                        $ids = $oldList->merge($newList)->unique();
                        $labels = ($dw['model'])::whereIn($dw['key'], $ids)
                            ->pluck($dw['label'], $dw['key']);
                        $mapFn = fn($id) => $labels[$id] ?? $id;
                    }

                    $changedFields[$requestKey] = [
                        'label' => Str::headline(str_replace('_ids', '', $requestKey)),
                        'removed' => $removed->map($mapFn)->implode(', '),
                        'added' => $added->map($mapFn)->implode(', '),
                        'old' => $oldList->map($mapFn)->implode(', '),
                        'new' => $newList->map($mapFn)->implode(', '),
                    ];
                }
            }

            // Handling for a single related model
            if (($config['type'] ?? null) === 'single') {
                $findBy = $config['find_by'] ?? [];
                $relationQuery = $model->{$relationName}();
                foreach ($findBy as $field => $valueKey) {
                    $relationQuery->where($field, $model->{$valueKey});
                }
                $existingRelatedModel = $relationQuery->first();
                $submittedRelationData = $validatedData[$requestKey];

                // If submittedRelationData is associative array (multiple fields)
                if (is_array($submittedRelationData)) {
                    foreach ($submittedRelationData as $field => $value) {
                        $originalValue = $existingRelatedModel ? $existingRelatedModel->{$field} : null;

                        if ($field === 'date_time' && ($originalValue || $value)) {
                            if (!$originalValue || !Carbon::parse($originalValue)->eq(Carbon::parse($value))) {
                                $changedFields["{$requestKey}.{$field}"] = [
                                    'label' => Str::headline($requestKey . ' Date Time'),
                                    'old' => $originalValue ? Carbon::parse($originalValue)->format('Y-m-d h:i A') : 'N/A',
                                    'new' => $value ? Carbon::parse($value)->format('Y-m-d h:i A') : 'N/A',
                                ];
                            }
                        } elseif ($originalValue != $value) {
                            $changedFields["{$requestKey}.{$field}"] = [
                                'label' => Str::headline($requestKey . ' ' . str_replace('_id', '', $field)),
                                'old' => $this->formatDisplayValue($originalValue),
                                'new' => $this->formatDisplayValue($value),
                            ];
                        }
                    }
                } else {
                    // If just scalar value (e.g. single id field)
                    $originalValue = $existingRelatedModel ? $existingRelatedModel->{$config['column']} : null;
                    $newValue = $submittedRelationData;

                    $displayOld = $originalValue;
                    $displayNew = $newValue;

                    if (isset($config['display_with'])) {
                        $dw = $config['display_with'];
                        $displayOld = $this->getDisplayLabel($dw['model'], $dw['key'], $dw['label'], $originalValue);
                        $displayNew = $this->getDisplayLabel($dw['model'], $dw['key'], $dw['label'], $newValue);
                    }

                    if ($displayOld != $displayNew) {
                        $changedFields[$requestKey] = [
                            'label' => Str::headline($requestKey),
                            'old' => $this->formatDisplayValue($displayOld),
                            'new' => $this->formatDisplayValue($displayNew),
                        ];
                    }
                }
            }
        }

        if (empty($changedFields)) {
            return response()->json([
                'status' => 'info',
                'message' => 'No changes were made.'
            ]);
        }

        return response()->json([
            'status' => 'confirmation_required',
            'changed_fields' => $changedFields
        ]);
    }
}
