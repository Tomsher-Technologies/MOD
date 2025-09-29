<?php

namespace App\Imports;

use App\Models\Country;
use App\Models\DropdownOption;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Http\Traits\HandlesUpdateConfirmation;
use App\Services\ImportLogService;
use Illuminate\Support\Facades\Log;

class CountryImport implements ToCollection, WithHeadingRow
{
    use HandlesUpdateConfirmation;

    protected $importLogService;
    protected $fileName;

    public function __construct($fileName = 'countries.xlsx')
    {
        $this->importLogService = new ImportLogService();
        $this->fileName = $fileName;
        $this->importLogService->clearLogs('countries');
    }

    public function collection(Collection $rows)
    {
        $rowNumber = 1; 
        
        foreach ($rows as $row) {
            $rowNumber++;
            
            try {
                $countryData = [
                    'name' => trim($row['name_en']) ?? null,
                    'name_ar' => trim($row['name_ar']) ?? null,
                    'short_code' => trim($row['short_code']) ?? null,
                    'sort_order' => trim($row['sort_order']) ?? null,
                    'status' => 1,
                ];

                if (!empty($row['continent_code'])) {
                    $continent = DropdownOption::whereHas('dropdown', function ($q) {
                        $q->where('code', 'continents');
                    })->where('code', trim($row['continent_code']))->first();

                    if ($continent) {
                        $countryData['continent_id'] = $continent->id;
                    } else {
                        $this->importLogService->logError('countries', $this->fileName, $rowNumber, 'Invalid continent_code: ' . $row['continent_code'], $row->toArray());
                        continue;
                    }
                }

                $country = Country::create($countryData);

                $this->logActivity(
                    module: 'Countries',
                    action: 'create-excel',
                    model: $country,
                    submodule: 'managing_countries',
                    submoduleId: $country->id
                );
                
                $this->importLogService->logSuccess('countries', $this->fileName, $rowNumber, $row->toArray());
            } catch (\Exception $e) {
                Log::error('Country Import Error: ' . $e->getMessage());
                $this->importLogService->logError('countries', $this->fileName, $rowNumber, $e->getMessage(), $row->toArray());
            }
        }
    }
}