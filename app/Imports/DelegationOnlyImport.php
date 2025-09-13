<?php

namespace App\Imports;

use App\Models\Delegation;
use App\Models\DropdownOption;
use App\Models\Country;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DelegationOnlyImport implements ToCollection, WithHeadingRow
{


    public function collection(Collection $rows)
    {

        DB::beginTransaction();

        try {
            foreach ($rows as $row) {

                if (empty($row['invitation_from_id'])) {
                    continue;
                }

                $delegationData = $this->processDelegationData($row);

                // $existingDelegation = Delegation::where('code', trim($row['code']))->first();

                // if ($existingDelegation) {
                //     $existingDelegation->update($delegationData);
                //     $delegation = $existingDelegation;
                // } else {
                $delegation = Delegation::create($delegationData);
                // }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delegation Only Import Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function processDelegationData($row)
    {
        $delegationData = [
            'invitation_from_id' => null,
            'continent_id' => null,
            'country_id' => null,
            'invitation_status_id' => null,
            'participation_status_id' => null,
            'note1' => trim($row['note1']) ?? null,
            'note2' => trim($row['note2']) ?? null,
        ];

        if (!empty($row['invitation_from_id'])) {
            $invitationFrom = DropdownOption::whereHas('dropdown', function ($q) {
                $q->where('code', 'departments');
            })->where('id', trim($row['invitation_from_id']))->first();

            if ($invitationFrom) {
                $delegationData['invitation_from_id'] = $invitationFrom->id;
            }
        }

        if (!empty($row['continent_id'])) {
            $continent = DropdownOption::whereHas('dropdown', function ($q) {
                $q->where('code', 'continents');
            })->where('id', trim($row['continent_id']))->first();

            if ($continent) {
                $delegationData['continent_id'] = $continent->id;
            }
        }

        if (!empty($row['country_id'])) {
            $country = Country::where('id', trim($row['country_id']))->first();

            if ($country) {
                $delegationData['country_id'] = $country->id;
            }
        }

        if (!empty($row['invitation_status_id'])) {
            $invitationStatus = DropdownOption::whereHas('dropdown', function ($q) {
                $q->where('code', 'invitation_status');
            })->where('id', trim($row['invitation_status_id']))->first();

            if ($invitationStatus) {
                $delegationData['invitation_status_id'] = $invitationStatus->id;
            }
        }

        if (!empty($row['participation_status_id'])) {
            $participationStatus = DropdownOption::whereHas('dropdown', function ($q) {
                $q->where('code', 'participation_status');
            })->where('id', trim($row['participation_status_id']))->first();

            if ($participationStatus) {
                $delegationData['participation_status_id'] = $participationStatus->id;
            }
        }

        return $delegationData;
    }
}
