<?php

namespace App\Imports;

use App\Models\Escort;
use App\Models\DropdownOption;
use App\Models\Delegation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Http\Traits\HandlesUpdateConfirmation;
use App\Services\ImportLogService;
use Illuminate\Support\Facades\Log;

class EscortImport implements ToCollection, WithHeadingRow
{
    use HandlesUpdateConfirmation;

    protected $importLogService;
    protected $fileName;

    public function __construct($fileName = 'escorts.xlsx')
    {
        $this->importLogService = new ImportLogService();
        $this->fileName = $fileName;
        $this->importLogService->clearLogs('escorts');
    }

    public function collection(Collection $rows)
    {
        $rowNumber = 1;

        foreach ($rows as $row) {
            $rowNumber++;

            try {
                $escortData = [
                    'name_en' => trim($row['name_en']) ?? null,
                    'name_ar' => trim($row['name_ar']) ?? null,
                    'military_number' => trim($row['military_number']) ?? null,
                    'phone_number' => trim($row['phone_number']) ?? null,
                    'internal_ranking_id' => null,
                    'unit_id' => null,
                    'status' => 1,
                ];

                if (empty($row['name_en']) && empty($row['name_ar'])) {
                    $this->importLogService->logError('escorts', $this->fileName, $rowNumber, 'Invalid name', $row->toArray());
                    continue;
                }

                if (!empty($row['gender_code'])) {
                    $gender = DropdownOption::whereHas('dropdown', function ($q) {
                        $q->where('code', 'gender');
                    })->where('code', trim($row['gender_code']))->first();

                    if ($gender) {
                        $escortData['gender_id'] = $gender->id;
                    } else {
                        $this->importLogService->logError('escorts', $this->fileName, $rowNumber, 'Invalid gender_code: ' . $row['gender_code'], $row->toArray());
                        continue;
                    }
                }

                if (!empty($row['internal_ranking_code'])) {
                    $ranking = DropdownOption::whereHas('dropdown', function ($q) {
                        $q->where('code', 'rank');
                    })->where('code', trim($row['internal_ranking_code']))->first();

                    if ($ranking) {
                        $escortData['internal_ranking_id'] = $ranking->id;
                    } else {
                        $this->importLogService->logError('escorts', $this->fileName, $rowNumber, 'Invalid internal_ranking_code: ' . $row['internal_ranking_code'], $row->toArray());
                        continue;
                    }
                }

                if (!empty($row['unit_code'])) {
                    $unit = DropdownOption::whereHas('dropdown', function ($q) {
                        $q->where('code', 'unit');
                    })->where('code', trim($row['unit_code']))->first();

                    if ($unit) {
                        $escortData['unit_id'] = $unit->id;
                    } else {
                        $this->importLogService->logError('escorts', $this->fileName, $rowNumber, 'Invalid unit_code: ' . $row['unit_code'], $row->toArray());
                        continue;
                    }
                }

                if (!empty($row['spoken_languages_codes'])) {
                    $languageCodes = explode(',', trim($row['spoken_languages_codes']));
                    $validLanguageIds = [];

                    foreach ($languageCodes as $languageCode) {

                        $lang = DropdownOption::whereHas('dropdown', function ($q) {
                            $q->where('code', 'spoken_languages');
                        })->where('code', trim($languageCode))->first();

                        if ($lang) {
                            $validLanguageIds[] = $lang->id;
                        }
                    }

                    if (!empty($validLanguageIds)) {
                        $escortData['spoken_languages'] = implode(',', $validLanguageIds);
                    }
                }

                if (isset($escortData['phone_number']) && !empty($escortData['phone_number'])) {
                    $escortData['phone_number'] = $escortData['phone_number'];
                }

                $escort = Escort::create($escortData);

                $this->logActivity(
                    module: 'Escorts',
                    action: 'create-excel',
                    model: $escort,
                    submodule: 'managing_members',
                    submoduleId: $escort->id
                );

                $this->importLogService->logSuccess('escorts', $this->fileName, $rowNumber, $row->toArray());
            } catch (\Exception $e) {
                Log::error('Escort Import Error: ' . $e->getMessage());
                $this->importLogService->logError('escorts', $this->fileName, $rowNumber, $e->getMessage(), $row->toArray());
            }
        }
    }
}
