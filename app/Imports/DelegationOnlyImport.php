<?php

namespace App\Imports;

use App\Http\Traits\HandlesUpdateConfirmation;
use App\Models\Delegation;
use App\Models\DropdownOption;
use App\Models\Country;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\DelegationStatusService;
use App\Services\ImportLogService;

class DelegationOnlyImport implements ToCollection, WithHeadingRow
{

    protected $delegationStatusService;
    protected $importLogService;
    protected $fileName;

    public function __construct($fileName = 'delegations.xlsx')
    {
        $this->delegationStatusService = new DelegationStatusService();
        $this->importLogService = new ImportLogService();
        $this->fileName = $fileName;
        $this->importLogService->clearLogs('delegations');
    }

    use HandlesUpdateConfirmation;

    public function collection(Collection $rows)
    {

        DB::beginTransaction();

        try {
            $rowNumber = 1;

            foreach ($rows as $row) {
                $rowNumber++;

                try {
                    if (empty($row['invitation_from_code'])) {
                        $this->importLogService->logError('delegations', $this->fileName, $rowNumber, 'Missing invitation_from_code', $row->toArray());
                        continue;
                    }

                    if (empty($row['country_code'])) {
                        $this->importLogService->logError('delegations', $this->fileName, $rowNumber, 'Missing country_code', $row->toArray());
                        continue;
                    }

                    $delegationData = $this->processDelegationData($row, $rowNumber);

                    $delegation = Delegation::create($delegationData);

                    $this->delegationStatusService->updateDelegationParticipationStatus($delegation);

                    $this->importLogService->logSuccess('delegations', $this->fileName, $rowNumber, $row->toArray());

                    // $this->logActivity(
                    //     module: 'Delegation',
                    //     action: 'create',
                    //     model: $delegation,
                    //     delegationId: $delegation->id
                    // );
            
                } catch (\Exception $e) {
                    Log::error('Delegation Only Import Error: ' . $e->getMessage());
                    $this->importLogService->logError('delegations', $this->fileName, $rowNumber, $e->getMessage(), $row->toArray());
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delegation Only Import Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function processDelegationData($row, $rowNumber)
    {
        $delegationData = [
            'invitation_from_id' => null,
            'continent_id' => null,
            'country_id' => null,
            'invitation_status_id' => null,
            'participation_status_id' => null,
            'import_code' => trim($row['import_code']) ?? null,
            'note1' => trim($row['note1']) ?? null,
            'note2' => trim($row['note2']) ?? null,
        ];

        if (!empty($row['invitation_from_code'])) {
            $invitationFrom = DropdownOption::whereHas('dropdown', function ($q) {
                $q->where('code', 'departments');
            })->where('code', trim($row['invitation_from_code']))->first();

            if ($invitationFrom) {
                $delegationData['invitation_from_id'] = $invitationFrom->id;
            } else {
                throw new \Exception("Invalid invitation_from_code: {$row['invitation_from_code']} (Row {$rowNumber})");
            }
        }

        if (!empty($row['continent_code'])) {
            $continent = DropdownOption::whereHas('dropdown', function ($q) {
                $q->where('code', 'continents');
            })->where('code', trim($row['continent_code']))->first();

            if ($continent) {
                $delegationData['continent_id'] = $continent->id;
            } else {
                throw new \Exception("Invalid continent_code: {$row['continent_code']} (Row {$rowNumber})");
            }
        }

        if (!empty($row['country_code'])) {
            $country = Country::where('code', trim($row['country_code']))->first();

            if ($country) {
                $delegationData['country_id'] = $country->id;
            } else {
                throw new \Exception("Invalid country_code: {$row['country_code']} (Row {$rowNumber})");
            }   
        }

        if (!empty($row['invitation_status_code'])) {
            $invitationStatus = DropdownOption::whereHas('dropdown', function ($q) {
                $q->where('code', 'invitation_status');
            })->where('code', trim($row['invitation_status_code']))->first();

            if ($invitationStatus) {
                $delegationData['invitation_status_id'] = $invitationStatus->id;
            } else {
                throw new \Exception("Invalid invitation_status_code: {$row['invitation_status_code']} (Row {$rowNumber})");
            }
        } else {
            $defaultInvitationStatus = DropdownOption::whereHas('dropdown', function ($q) {
                $q->where('code', 'invitation_status');
            })->where('code', '1')->first();

            $delegationData['invitation_status_id'] = $defaultInvitationStatus->id;
        }

        return $delegationData;
    }
}
