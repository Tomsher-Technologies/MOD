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
            $isChanged = false;
            $isBooleanField = $model->hasCast($key, 'boolean');

            if ($isBooleanField) {
                $isChanged = (bool) $originalValue !== (bool) $newValue;
            } else {
                $isChanged = $originalValue != $newValue;
            }

            if ($isChanged) {
                $changedFields[$key] = [
                    'label' => Str::headline(str_replace('_id', '', $key)),
                    'old'   => $this->formatDisplayValue($originalValue, $isBooleanField),
                    'new'   => $this->formatDisplayValue($newValue, $isBooleanField),
                ];
            }
        }

        foreach ($relationsToCompare as $requestKey => $config) {
            if (!isset($validatedData[$requestKey])) continue;
            $relationName = $config['relation'];
            $findBy = $config['find_by'];
            $existingRelatedModel = $model->{$relationName}()->where($findBy)->first();
            $submittedRelationData = $validatedData[$requestKey];

            foreach ($submittedRelationData as $key => $value) {
                $originalValue = $existingRelatedModel ? $existingRelatedModel->{$key} : null;

                if ($key === 'date_time' && ($originalValue || $value)) {
                    if (!$originalValue || !Carbon::parse($originalValue)->eq(Carbon::parse($value))) {
                        $changedFields["{$requestKey}.{$key}"] = [
                            'label' => Str::headline($requestKey . ' Date Time'),
                            'old' => $originalValue ? Carbon::parse($originalValue)->format('Y-m-d h:i A') : 'N/A',
                            'new' => $value ? Carbon::parse($value)->format('Y-m-d h:i A') : 'N/A',
                        ];
                    }
                } elseif ($originalValue != $value) {
                    $changedFields["{$requestKey}.{$key}"] = [
                        'label' => Str::headline($requestKey . ' ' . str_replace('_id', '', $key)),
                        'old'   => $this->formatDisplayValue($originalValue),
                        'new'   => $this->formatDisplayValue($value),
                    ];
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
