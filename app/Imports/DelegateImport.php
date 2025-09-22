<?php

namespace App\Imports;

use App\Http\Traits\HandlesUpdateConfirmation;
use App\Models\Delegation;
use App\Models\Delegate;
use App\Models\DelegateTransport;
use App\Models\DropdownOption;
use Error;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DelegateImport implements ToCollection, WithHeadingRow
{

    use HandlesUpdateConfirmation;

    public function collection(Collection $rows)
    {
        DB::beginTransaction();


        try {
            foreach ($rows as $row) {


                $delegationCode = trim($row['delegation_code'] ?? '');

                if (empty($delegationCode)) {
                    continue;
                }

                $delegation = Delegation::where('code', $delegationCode)->first();

                if (!$delegation) {
                    continue;
                }

                $delegateData = $this->processDelegateData($row, $delegation->id);

                // $delegateCode = trim($row['delegate_code'] ?? '');
                $existingDelegate = null;

                // if (!empty($delegateCode)) {
                //     $existingDelegate = Delegate::where('delegation_id', $delegation->id)
                //         ->where('code', $delegateCode)
                //         ->first();
                // }

                if ($existingDelegate) {
                    $existingDelegate->update($delegateData);
                    $delegate = $existingDelegate;
                } else {
                    $delegate = Delegate::create($delegateData);

                    $this->logActivity(
                        module: 'Delegation',
                        submodule: 'delegate',
                        action: 'create-excel',
                        model: $delegate,
                        submoduleId: $delegate->id,
                        delegationId: $delegation->id
                    );
                }

                if (!empty($row['arrival_date_time'])) {
                    $this->processTransport($delegate, $row, 'arrival');
                }

                if (!empty($row['departure_date_time'])) {
                    $this->processTransport($delegate, $row, 'departure');
                }

                $this->updateParticipationStatus($delegate);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delegate Import Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function processDelegateData($row, $delegationId)
    {
        $delegateData = [
            'delegation_id' => $delegationId,
            'title_en' => trim($row['delegate_title_en']) ?? null,
            'title_ar' => trim($row['delegate_title_ar']) ?? null,
            'name_en' => trim($row['delegate_name_en']) ?? null,
            'name_ar' => trim($row['delegate_name_ar']) ?? null,
            'gender_id' => null,
            'designation_en' => trim($row['delegate_designation_en']) ?? null,
            'designation_ar' => trim($row['delegate_designation_ar']) ?? null,
            'parent_id' => null,
            'note' => trim($row['delegate_note']) ?? null,
            'relationship_id' => null,
            'internal_ranking_id' => null,
            'accommodation' => !empty($row['delegate_accommodation']) && strtolower($row['delegate_accommodation']) === 'yes' ? 1 : 0,
            'team_head' => !empty($row['delegate_team_head']) && strtolower($row['delegate_team_head']) === 'yes' ? 1 : 0,
            'badge_printed' => !empty($row['delegate_badge_printed']) && strtolower($row['delegate_badge_printed']) === 'yes' ? 1 : 0,
        ];


        if (!empty($row['delegate_gender_id'])) {
            $gender = DropdownOption::whereHas('dropdown', function ($q) {
                $q->where('code', 'gender');
            })->where('id', trim($row['delegate_gender_id']))->first();

            if ($gender) {
                $delegateData['gender_id'] = $gender->id;
            }
        }

        if (!empty($row['delegate_relationship_id'])) {
            $relationship = DropdownOption::whereHas('dropdown', function ($q) {
                $q->where('code', 'relationship');
            })->where('id', trim($row['delegate_relationship_id']))->first();

            if ($relationship) {
                $delegateData['relationship_id'] = $relationship->id;
            }
        }

        if (!empty($row['delegate_internal_ranking_id'])) {
            $ranking = DropdownOption::whereHas('dropdown', function ($q) {
                $q->where('code', 'internal_ranking');
            })->where('id', trim($row['delegate_internal_ranking_id']))->first();

            if ($ranking) {
                $delegateData['internal_ranking_id'] = $ranking->id;
            }
        }

        if (!empty($row['delegate_parent_code'])) {
            $parentDelegate = Delegate::where('delegation_id', $delegationId)
                ->where('code', trim($row['delegate_parent_code']))
                ->first();

            if ($parentDelegate) {
                $delegateData['parent_id'] = $parentDelegate->id;
            }
        }

        return $delegateData;
    }

    private function processTransport($delegate, $row, $type)
    {
        $fieldPrefix = $type === 'arrival' ? 'arrival_' : 'departure_';

        $transportData = [
            'delegate_id' => $delegate->id,
            'type' => $type,
            'mode' => trim($row[$fieldPrefix . 'mode']) ?? null,
            'airport_id' => null,
            'flight_no' => trim($row[$fieldPrefix . 'flight_no']) ?? null,
            'flight_name' => trim($row[$fieldPrefix . 'flight_name']) ?? null,
            'date_time' => null,
            'status' => trim($row[$fieldPrefix . 'status']) ?? null,
            'comment' => trim($row[$fieldPrefix . 'comment']) ?? null,
        ];

        if (!empty($row[$fieldPrefix . 'date_time'])) {
            try {
                if (
                    is_string($row[$fieldPrefix . 'date_time']) &&
                    preg_match('/^\d{4}-\d{2}-\d{2}[\sT]\d{2}:\d{2}:\d{2}/', $row[$fieldPrefix . 'date_time'])
                ) {
                    $transportData['date_time'] = $row[$fieldPrefix . 'date_time'];
                } elseif (is_numeric($row[$fieldPrefix . 'date_time'])) {
                    $transportData['date_time'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[$fieldPrefix . 'date_time'])->format('Y-m-d H:i:s');
                } else {
                    $dateTime = new \DateTime($row[$fieldPrefix . 'date_time']);
                    $transportData['date_time'] = $dateTime->format('Y-m-d H:i:s');
                }
            } catch (\Exception $e) {
                $transportData['date_time'] = null;
            }
        }

        if (!empty($row[$fieldPrefix . 'airport_id']) && $transportData['mode'] === 'flight') {
            $airport = DropdownOption::whereHas('dropdown', function ($q) {
                $q->where('code', 'airports');
            })->where('id', trim($row[$fieldPrefix . 'airport_id']))->first();

            if ($airport) {
                $transportData['airport_id'] = $airport->id;
            }
        }

        $delegate->delegateTransports()->updateOrCreate(
            ['type' => $type],
            $transportData
        );
    }

    protected function updateParticipationStatus($delegate)
    {
        if (!$delegate->exists) {
            $delegate->participation_status = 'to_be_arrived';
            $delegate->save();
            return;
        }

        $arrivalTransport = $delegate->delegateTransports()->where('type', 'arrival')->latest('date_time')->first();
        $departureTransport = $delegate->delegateTransports()->where('type', 'departure')->latest('date_time')->first();

        $newStatus = $delegate->participation_status ?? 'to_be_arrived';

        if ($departureTransport && $departureTransport->status) {
            if ($departureTransport->status === 'to_be_departed' || $departureTransport->status === 'departed') {
                $newStatus = $departureTransport->status;
            } elseif ($arrivalTransport && $arrivalTransport->status) {
                $newStatus = $arrivalTransport->status;
            }
        } elseif ($arrivalTransport && $arrivalTransport->status) {
            $newStatus = $arrivalTransport->status;
        } else {
            $newStatus = 'to_be_arrived';
        }

        if ($delegate->participation_status !== $newStatus) {
            $delegate->participation_status = $newStatus;
            $delegate->save();
        }
    }
}
