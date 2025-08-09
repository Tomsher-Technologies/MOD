<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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

    protected function processUpdate(
        Request $request,
        Model $model,
        array $validatedData,
        array $relationsToCompare = []
    ) {
        if ($request->has('_is_confirmed')) {
            return [
                'data' => $validatedData,
                'notify' => $request->input('_notify_fields', []),
            ];
        }

        $changedFields = [];
        $mainModelData = Arr::only($validatedData, $model->getFillable());
        $originalMainData = $model->getOriginal();

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
                $changedFields[$key] = [
                    'label' => Str::headline(str_replace('_id', '', $key)),
                    'old'   => $this->formatDisplayValue($originalValue, $isBooleanField),
                    'new'   => $this->formatDisplayValue($newValue, $isBooleanField),
                ];
            }
        }


        foreach ($relationsToCompare as $requestKey => $config) {

            if (!isset($validatedData[$requestKey])) {
                continue;
            }
            $relationName = $config['relation'];

            // Special handling for list/checkbox relations (sync tables)
            if (($config['type'] ?? null) === 'list' && isset($config['column'])) {
                $oldList = $model->{$relationName}()->pluck($config['column'])->sort()->values();
                $newList = collect($validatedData[$requestKey])->map(fn($v) => (int)$v)->sort()->values();

                $removed = $oldList->diff($newList);
                $added   = $newList->diff($oldList);

                if ($removed->isNotEmpty() || $added->isNotEmpty()) {
                    $changedFields[$requestKey] = [
                        'label'   => Str::headline(str_replace('_ids', '', $requestKey)),
                        'removed' => $removed->implode(', '),
                        'added'   => $added->implode(', '),
                        'old'     => $oldList->implode(', '),
                        'new'     => $newList->implode(', '),
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

                foreach ($submittedRelationData as $field => $value) {
                    $originalValue = $existingRelatedModel ? $existingRelatedModel->{$field} : null;

                    if ($field === 'date_time' && ($originalValue || $value)) {
                        if (!$originalValue || !Carbon::parse($originalValue)->eq(Carbon::parse($value))) {
                            $changedFields["{$requestKey}.{$field}"] = [
                                'label' => Str::headline($requestKey . ' Date Time'),
                                'old'   => $originalValue ? Carbon::parse($originalValue)->format('Y-m-d h:i A') : 'N/A',
                                'new'   => $value ? Carbon::parse($value)->format('Y-m-d h:i A') : 'N/A',
                            ];
                        }
                    } elseif ($originalValue != $value) {
                        $changedFields["{$requestKey}.{$field}"] = [
                            'label' => Str::headline($requestKey . ' ' . str_replace('_id', '', $field)),
                            'old'   => $this->formatDisplayValue($originalValue),
                            'new'   => $this->formatDisplayValue($value),
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
