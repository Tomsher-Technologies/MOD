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
    private function formatDisplayValue($value, $field = null)
    {
        if ($field === 'status') {
            return $value == 1 ? 'Active' : 'Inactive';
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if ($value === null || $value === '') {
            return 'N/A';
        }

        return $value;
    }

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
        array $relationsToCompare = [],
        array $customChangedFields = []
    ) {
        if ($request->has('_is_confirmed')) {
            // Already confirmed, just proceed
            return [
                'data' => $validatedData,
                'notify' => $request->input('_notify_fields', []),
            ];
        }

        $changedFields = $customChangedFields ? [...$customChangedFields] : [];

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
                        'old' => $this->formatDisplayValue($displayOld, $key),
                        'new' => $this->formatDisplayValue($displayNew, $key),
                    ];
                    continue;
                }

                $changedFields[$key] = [
                    'label' => Str::headline(str_replace('_id', '', $key)),
                    'old' => $this->formatDisplayValue($originalValue, $key),
                    'new' => $this->formatDisplayValue($newValue, $key),
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
            } elseif (isset($config['relation']) && isset($config['find_by'])) {
                $relationQuery = $model->{$relationName}();
                foreach ($config['find_by'] as $field => $value) {
                    $relationQuery->where($field, $value);
                }
                $existingRelatedModel = $relationQuery->first();
                $submittedRelationData = $validatedData[$requestKey];

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
                            $displayOld = $originalValue;
                            $displayNew = $value;

                            if (isset($config['display_with'][$field])) {
                                $dw = $config['display_with'][$field];
                                $displayOld = $this->getDisplayLabel($dw['model'], $dw['key'], $dw['label'], $originalValue);
                                $displayNew = $this->getDisplayLabel($dw['model'], $dw['key'], $dw['label'], $value);
                            }

                            $changedFields["{$requestKey}.{$field}"] = [
                                'label' => Str::headline($requestKey . ' ' . str_replace('_id', '', $field)),
                                'old' => $this->formatDisplayValue($displayOld),
                                'new' => $this->formatDisplayValue($displayNew),
                            ];
                        }
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

    protected function logActivity(
        string $module,
        string $action,
        ?Model $model = null,
        ?int $userId = null,
        ?array $changedFields = null,
        ?string $message = null,
        ?int $currentEventId = null,
        string $activityModelClass = \App\Models\DelegationActivity::class,
        ?string $submodule = null,
        ?int $submoduleId = null,
        ?int $delegationId = null
    ): void {
        // Try to get the current event ID from various sources
        $eventId = $currentEventId;

        if (!$eventId && $model) {
            // If the model has an event_id field, use it
            if ($model->hasAttribute('event_id') && $model->event_id) {
                $eventId = $model->event_id;
            }
            // For delegation-related models, try to get event from delegation
            elseif ($model instanceof \App\Models\Delegate && $model->delegation && $model->delegation->event_id) {
                $eventId = $model->delegation->event_id;
            } elseif ($model instanceof \App\Models\Interview && $model->delegation && $model->delegation->event_id) {
                $eventId = $model->delegation->event_id;
            } elseif ($model instanceof \App\Models\DelegationAttachment && $model->delegation && $model->delegation->event_id) {
                $eventId = $model->delegation->event_id;
            }
        }

        if (!$eventId) {
            $defaultEvent = \App\Models\Event::where('is_default', true)->first();
            $eventId = $defaultEvent ? $defaultEvent->id : null;
        }

        $activityData = [
            'event_id' => $eventId,
            'module' => $module,
            'submodule' => $submodule,
            'action' => $action,
            'submodule_id' => $submoduleId,
            'delegation_id' => $delegationId,
            'user_id' => $userId ?? auth()->id(),
            'changes' => $changedFields,
            'message' => $message ?? $this->generateActivityMessage($action, $module, $changedFields),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        try {
            $activityModelClass::create($activityData);
        } catch (\Throwable $e) {
            Log::error("Failed to log activity for {$module} - {$e->getMessage()}");
        }
    }

    protected function generateActivityMessage(string $action, string $module, ?array $changedFields = null): string
    {
        $user = auth()->user();
        $userName = $user ? $user->name : 'Someone';

        if ($action === 'create') {
            return "{$module} was created by {$userName}.";
        }
        if ($action === 'create-excel') {
            return "{$module} was created by {$userName} from an Excel file.";
        }
        if ($action === 'update' && $changedFields) {
            $changesSummary = collect($changedFields)
                ->map(fn($change, $key) => "{$change['label']}: '{$change['old']}' => '{$change['new']}'")
                ->implode('; ');
            return "{$userName} updated {$module}: {$changesSummary}.";
        }
        if ($action === 'delete') {
            return "{$module} was deleted by {$userName}.";
        }

        if ($action === 'assign-room' && $changedFields) {
            $member_name = $changedFields['member_name'] ?? 'Unknown Delegate';
            $hotelName = $changedFields['hotel_name'] ?? 'Unknown Hotel';
            $roomNumber = $changedFields['room_number'] ?? 'N/A';

            return "{$userName} assigned {$member_name} to Room ({$roomNumber}) at {$hotelName}.";
        }

        if ($action === 'assign-escorts' && $changedFields) {
            $escortName = $changedFields['escort_name'] ?? 'Unknown Escort';
            $delegationCode = $changedFields['delegation_code'] ?? 'Unknown Delegation';

            return "{$userName} assigned escort {$escortName} to delegation {$delegationCode}.";
        }

        if ($action === 'unassign-escorts' && $changedFields) {
            $escortName = $changedFields['escort_name'] ?? 'Unknown Escort';
            $delegationCode = $changedFields['delegation_code'] ?? 'Unknown Delegation';

            return "{$userName} unassigned escort {$escortName} from delegation {$delegationCode}.";
        }

        if ($action === 'assign-drivers' && $changedFields) {
            $driverName = $changedFields['driver_name'] ?? 'Unknown Driver';
            $delegationCode = $changedFields['delegation_code'] ?? 'Unknown Delegation';

            return "{$userName} assigned driver {$driverName} to delegation {$delegationCode}.";
        }

        if ($action === 'unassign-drivers' && $changedFields) {
            $driverName = $changedFields['driver_name'] ?? 'Unknown Driver';
            $delegationCode = $changedFields['delegation_code'] ?? 'Unknown Delegation';

            return "{$userName} unassigned driver {$driverName} from delegation {$delegationCode}.";
        }

       


        return "{$userName} performed {$action} on {$module}.";
    }
}
