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
    private function formatDisplayValue($value, $field = null, $model = null)
    {
        if ($field === 'status') {
            if ($model instanceof \App\Models\DelegateTransport) {
                return humanize($value) ?? 'N/A';
            }

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
            return [
                'data' => $validatedData,
                'notify' => $request->input('_notify_fields', []),
            ];
        }

        $changedFields = $customChangedFields ? [...$customChangedFields] : [];

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
                : ($this->normalizeForComparison($originalValue) != $this->normalizeForComparison($newValue));

            if ($isChanged) {
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
                    'old' => $this->formatDisplayValue($originalValue, $key, $model),
                    'new' => $this->formatDisplayValue($newValue, $key, $model),
                ];
            }
        }

        foreach ($relationsToCompare as $requestKey => $config) {
            if (!isset($validatedData[$requestKey])) {
                continue;
            }
            $relationName = $config['relation'] ?? null;

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

            if (($config['type'] ?? null) === 'single') {
                $findBy = $config['find_by'] ?? [];
                $relationQuery = $model->{$relationName}();
                foreach ($findBy as $field => $valueKey) {
                    $relationQuery->where($field, $model->{$valueKey});
                }
                $existingRelatedModel = $relationQuery->first();
                $submittedRelationData = $validatedData[$requestKey];

                if (is_array($submittedRelationData)) {
                    foreach ($submittedRelationData as $field => $value) {
                        $originalValue = $existingRelatedModel ? $existingRelatedModel->{$field} : null;

                        $originalNormalized = $this->normalizeForComparison($originalValue);
                        $newNormalized = $this->normalizeForComparison($value);

                        if ($originalNormalized != $newNormalized) {
                            $oldDisplay = $originalValue;
                            $newDisplay = $value;

                            try {
                                if ($originalValue) $oldDisplay = Carbon::parse($originalValue)->format('Y-m-d H:i');
                                if ($value) $newDisplay = Carbon::parse($value)->format('Y-m-d H:i');
                            } catch (\Exception $e) {
                            }

                            $changedFields["{$requestKey}.{$field}"] = [
                                'label' => Str::headline($requestKey . ' ' . str_replace('_id', '', $field)),
                                'old' => $this->formatDisplayValue($oldDisplay),
                                'new' => $this->formatDisplayValue($newDisplay),
                            ];
                        }
                    }
                } else {
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
                                    'old' => $originalValue ? Carbon::parse($originalValue)->format('Y-m-d H:i') : 'N/A',
                                    'new' => $value ? Carbon::parse($value)->format('Y-m-d H:i') : 'N/A',
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
        ?int $delegationId = null,
        ?array $fieldsToNotify = null
    ): void {
        $eventId = $currentEventId;

        if (!$eventId && $model) {
            if ($model->hasAttribute('event_id') && $model->event_id) {
                $eventId = $model->event_id;
            } elseif ($model instanceof \App\Models\Delegate && $model->delegation && $model->delegation->event_id) {
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
            $activity = $activityModelClass::create($activityData);

            if (in_array($action, ['create', 'create-excel', 'assign-escorts', 'unassign-escorts', 'assign-drivers', 'unassign-drivers', 'assign-room'])) {
                $this->sendNotification($activity, $action, $module, $delegationId);
            } elseif ($action === 'update' && !empty($fieldsToNotify) && !empty($changedFields)) {
                $notifiedChanges = array_intersect_key($changedFields, array_flip($fieldsToNotify));

                if (!empty($notifiedChanges)) {
                    $notificationActivity = clone $activity;
                    $notificationActivity->changes = $notifiedChanges;
                    $notificationActivity->message = $this->generateActivityMessage($action, $module, $notifiedChanges);
                    $this->sendNotification($notificationActivity, $action, $module, $delegationId);
                }
            }
        } catch (\Throwable $e) {
            Log::error("Failed to log activity for {$module} - {$e->getMessage()}");
        }
    }

    protected function generateActivityMessage(string $action, string $module, ?array $changedFields = null): array
    {
        $user = auth()->user();
        $userName = $user ? $user->name : 'Someone';

        if ($action === 'create') {
            return [
                'en' => "{$module} was created by {$userName}.",
                'ar' => "تم إنشاء {$module} بواسطة {$userName}."
            ];
        }
        if ($action === 'create-excel') {
            return [
                'en' => "{$module} was created by {$userName} from an Excel file.",
                'ar' => "تم إنشاء {$module} بواسطة {$userName} من ملف Excel."
            ];
        }
        if ($action === 'update' && $changedFields) {
            $changeCount = count($changedFields);
            $changesText = $changeCount == 1 ? '1 field' : "{$changeCount} fields";
            $changesTextAr = $changeCount == 1 ? 'حقل واحد' : "{$changeCount} حقول";
            
            return [
                'en' => "{$userName} updated {$module} ({$changesText} changed).",
                'ar' => "{$userName} قام بتحديث {$module} (تم تغيير {$changesTextAr})."
            ];
        }
        if ($action === 'delete') {
            return [
                'en' => "{$module} was deleted by {$userName}.",
                'ar' => "تم حذف {$module} بواسطة {$userName}."
            ];
        }

        if ($action === 'assign-room' && $changedFields) {
            $member_name = $changedFields['member_name'] ?? 'Unknown Delegate';
            $hotelName = $changedFields['hotel_name'] ?? 'Unknown Hotel';
            $roomNumber = $changedFields['room_number'] ?? 'N/A';

            return [
                'en' => "{$userName} assigned {$member_name} to Room ({$roomNumber}) at {$hotelName}.",
                'ar' => "{$userName} قام بتعيين {$member_name} في الغرفة ({$roomNumber}) في {$hotelName}."
            ];
        }

        if ($action === 'assign-escorts' && $changedFields) {
            $escortName = $changedFields['escort_name'] ?? 'Unknown Escort';
            $delegationCode = $changedFields['delegation_code'] ?? 'Unknown Delegation';

            return [
                'en' => "{$userName} assigned escort {$escortName} to delegation {$delegationCode}.",
                'ar' => "{$userName} قام بتعيين المرافق {$escortName} للوفد {$delegationCode}."
            ];
        }

        if ($action === 'unassign-escorts' && $changedFields) {
            $escortName = $changedFields['escort_name'] ?? 'Unknown Escort';
            $delegationCode = $changedFields['delegation_code'] ?? 'Unknown Delegation';

            return [
                'en' => "{$userName} unassigned escort {$escortName} from delegation {$delegationCode}.",
                'ar' => "{$userName} قام بإلغاء تعيين المرافق {$escortName} من الوفد {$delegationCode}."
            ];
        }

        if ($action === 'assign-drivers' && $changedFields) {
            $driverName = $changedFields['driver_name'] ?? 'Unknown Driver';
            $delegationCode = $changedFields['delegation_code'] ?? 'Unknown Delegation';

            return [
                'en' => "{$userName} assigned driver {$driverName} to delegation {$delegationCode}.",
                'ar' => "{$userName} قام بتعيين السائق {$driverName} للوفد {$delegationCode}."
            ];
        }

        if ($action === 'unassign-drivers' && $changedFields) {
            $driverName = $changedFields['driver_name'] ?? 'Unknown Driver';
            $delegationCode = $changedFields['delegation_code'] ?? 'Unknown Delegation';

            return [
                'en' => "{$userName} unassigned driver {$driverName} from delegation {$delegationCode}.",
                'ar' => "{$userName} قام بإلغاء تعيين السائق {$driverName} من الوفد {$delegationCode}."
            ];
        }

        return [
            'en' => "{$userName} performed {$action} on {$module}.",
            'ar' => "{$userName} قام ب{$action} على {$module}."
        ];
    }

    protected function sendNotification($activity, $action, $module, $delegationId = null)
    {
        $notificationData = [
            'delegation_id' => $delegationId,
            'message' => $activity->message,
            'module' => $module,
            'action' => $action,
            'changes' => $activity->changes,
            'created_at' => $activity->created_at,
        ];

        $currentEventId = session('current_event_id', getDefaultEventId() ?? null);
        
        if (!$currentEventId) {
            return;
        }
        
        $users = \App\Models\EventUserRole::with('user')
            ->where('event_id', $currentEventId)
            ->get()
            ->pluck('user')
            ->unique('id');

        foreach ($users as $user) {
            $user->notify(new \App\Notifications\CommonNotification($notificationData));
        }
    }

    private function normalizeForComparison($value, $field = null)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (Str::contains($field, 'date') || $value instanceof \Carbon\Carbon) {
            try {
                return Carbon::parse($value)->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                return $value;
            }
        }

        return $value;
    }
}
