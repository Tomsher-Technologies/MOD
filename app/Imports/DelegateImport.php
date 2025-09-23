<?php

namespace App\Imports;

use App\Http\Traits\HandlesUpdateConfirmation;
use App\Models\Delegation;
use App\Models\Delegate;
use App\Models\DelegateTransport;
use App\Models\DropdownOption;
use App\Services\DelegationStatusService;
use Error;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\ImportLogService;

class DelegateImport implements ToCollection, WithHeadingRow
{

    protected $delegationStatusService;
    protected $importLogService;
    protected $fileName;

    public function __construct($fileName = 'delegates.xlsx')
    {
        $this->delegationStatusService = new DelegationStatusService();
        $this->importLogService = new ImportLogService();
        $this->fileName = $fileName;
        $this->importLogService->clearLogs('delegates');
    }

    use HandlesUpdateConfirmation;

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            $processedDelegations = [];
            $rowNumber = 1;

            foreach ($rows as $row) {
                $rowNumber++;

                try {
                    $delegationCode = trim($row['delegation_code'] ?? '');

                    if (empty($delegationCode)) {
                        $this->importLogService->logError('delegates', $this->fileName, $rowNumber, 'Missing delegation_code', $row->toArray());
                        continue;
                    }

                    $delegation = Delegation::where('code', $delegationCode)->first();

                    if (!$delegation) {
                        $this->importLogService->logError('delegates', $this->fileName, $rowNumber, 'Delegation not found with code: ' . $delegationCode, $row->toArray());
                        continue;
                    }

                    $delegateData = $this->processDelegateData($row, $delegation->id, $rowNumber);

                    $arrivalData = $this->processTransportData($row, 'arrival', $rowNumber);
                    $departureData = $this->processTransportData($row, 'departure', $rowNumber);

                    $delegate = Delegate::create($delegateData);

                    $this->delegationStatusService->syncTransportInfo($delegate, $arrivalData, 'arrival');
                    $this->delegationStatusService->syncTransportInfo($delegate, $departureData, 'departure');

                    $this->delegationStatusService->updateAllStatus($delegate);

                    $processedDelegations[$delegation->id] = $delegation;

                    $this->logActivity(
                        module: 'Delegation',
                        submodule: 'delegate',
                        action: 'create-excel',
                        model: $delegate,
                        submoduleId: $delegate->id,
                        delegationId: $delegation->id
                    );

                    $this->importLogService->logSuccess('delegates', $this->fileName, $rowNumber, $row->toArray());
                } catch (\Exception $e) {
                    Log::error('Delegate Import Error: ' . $e->getMessage());
                    $this->importLogService->logError('delegates', $this->fileName, $rowNumber, $e->getMessage(), $row->toArray());
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delegate Import Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function processDelegateData($row, $delegationId, $rowNumber)
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
            } else {
                $this->importLogService->logError('delegates', $this->fileName, $rowNumber, 'Invalid delegate_gender_id: ' . $row['delegate_gender_id'], $row->toArray());
            }
        }

        if (!empty($row['delegate_relationship_id'])) {
            $relationship = DropdownOption::whereHas('dropdown', function ($q) {
                $q->where('code', 'relationship');
            })->where('id', trim($row['delegate_relationship_id']))->first();

            if ($relationship) {
                $delegateData['relationship_id'] = $relationship->id;
            } else {
                $this->importLogService->logError('delegates', $this->fileName, $rowNumber, 'Invalid delegate_relationship_id: ' . $row['delegate_relationship_id'], $row->toArray());
            }
        }

        if (!empty($row['delegate_internal_ranking_id'])) {
            $ranking = DropdownOption::whereHas('dropdown', function ($q) {
                $q->where('code', 'internal_ranking');
            })->where('id', trim($row['delegate_internal_ranking_id']))->first();

            if ($ranking) {
                $delegateData['internal_ranking_id'] = $ranking->id;
            } else {
                $this->importLogService->logError('delegates', $this->fileName, $rowNumber, 'Invalid delegate_internal_ranking_id: ' . $row['delegate_internal_ranking_id'], $row->toArray());
            }
        }

        if (!empty($row['delegate_parent_code'])) {
            $parentDelegate = Delegate::where('delegation_id', $delegationId)
                ->where('code', trim($row['delegate_parent_code']))
                ->first();

            if ($parentDelegate) {
                $delegateData['parent_id'] = $parentDelegate->id;
            } else {
                $this->importLogService->logError('delegates', $this->fileName, $rowNumber, 'Parent delegate not found with code: ' . $row['delegate_parent_code'], $row->toArray());
            }
        }

        return $delegateData;
    }

    private function processTransportData($row, $type, $rowNumber)
    {
        $fieldPrefix = $type . '_';

        $hasData = false;
        foreach (['mode', 'airport_id', 'flight_no', 'flight_name', 'date_time', 'comment'] as $field) {
            if (!empty($row[$fieldPrefix . $field])) {
                $hasData = true;
                break;
            }
        }

        if (!$hasData) {
            return null;
        }

        $transportData = [
            'mode' => trim($row[$fieldPrefix . 'mode'] ?? '') ?: null,
            'airport_id' => !empty($row[$fieldPrefix . 'airport_id']) ? trim($row[$fieldPrefix . 'airport_id']) : null,
            'flight_no' => trim($row[$fieldPrefix . 'flight_no'] ?? '') ?: null,
            'flight_name' => trim($row[$fieldPrefix . 'flight_name'] ?? '') ?: null,
            'date_time' => null,
            'comment' => trim($row[$fieldPrefix . 'comment'] ?? '') ?: null,
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

        if (!empty($transportData['mode']) && $transportData['mode'] === 'flight' && !empty($transportData['airport_id'])) {
            $airport = DropdownOption::whereHas('dropdown', function ($q) {
                $q->where('code', 'airports');
            })->where('id', trim($transportData['airport_id']))->first();

            if (!$airport) {
                $this->importLogService->logError('delegates', $this->fileName, $rowNumber, 'Invalid ' . $type . ' airport_id: ' . $transportData['airport_id'], $row->toArray());
                $transportData['airport_id'] = null;
            }
        } elseif ($transportData['mode'] !== 'flight') {
            $transportData['airport_id'] = null;
        }

        return $transportData;
    }
}
