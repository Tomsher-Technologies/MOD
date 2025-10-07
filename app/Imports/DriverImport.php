<?php

namespace App\Imports;

use App\Models\Driver;
use App\Models\DropdownOption;
use App\Models\Delegation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Http\Traits\HandlesUpdateConfirmation;
use App\Services\ImportLogService;
use Illuminate\Support\Facades\Log;

class DriverImport implements ToCollection, WithHeadingRow
{
    use HandlesUpdateConfirmation;

    protected $importLogService;
    protected $fileName;

    public function __construct($fileName = 'drivers.xlsx')
    {
        $this->importLogService = new ImportLogService();
        $this->fileName = $fileName;
        $this->importLogService->clearLogs('drivers');
    }

    public function collection(Collection $rows)
    {
        $rowNumber = 1;

        foreach ($rows as $row) {
            $rowNumber++;

            try {
                $driverData = [
                    'name_en' => trim($row['name_en']) ?? null,
                    'name_ar' => trim($row['name_ar']) ?? null,
                    'military_number' => trim($row['military_number']) ?? null,
                    'phone_number' => trim($row['phone_number']) ?? null,
                    'car_type' => trim($row['car_type']) ?? null,
                    'car_number' => trim($row['car_number']) ?? null,
                    'capacity' => trim($row['capacity']) ?? null,
                    'note1' => trim($row['note1']) ?? null,
                    'title_en' => trim($row['title_en']) ?? null,
                    'title_ar' => trim($row['title_ar']) ?? null,
                    'unit_id' => null,
                    'status' => 1,
                ];

                if (empty($row['name_en']) && empty($row['name_ar'])) {
                    $this->importLogService->logError('drivers', $this->fileName, $rowNumber, 'Invalid name', $row->toArray());
                    continue;
                }

                if (!empty($row['unit_code'])) {
                    $unit = DropdownOption::whereHas('dropdown', function ($q) {
                        $q->where('code', 'unit');
                    })->where('code', trim($row['unit_code']))->first();

                    if ($unit) {
                        $driverData['unit_id'] = $unit->id;
                    } else {
                        $this->importLogService->logError('drivers', $this->fileName, $rowNumber, 'Invalid unit_code: ' . $row['unit_code'], $row->toArray());
                        continue;
                    }
                }

                if (isset($driverData['phone_number']) && !empty($driverData['phone_number'])) {
                    $phoneNumber = preg_replace('/[^0-9]/', '', $driverData['phone_number']);
                    if (strlen($phoneNumber) === 9) {
                        $driverData['phone_number'] = '971' . $phoneNumber;
                    }
                }

                $driver = Driver::create($driverData);

                $this->logActivity(
                    module: 'Drivers',
                    action: 'create-excel',
                    model: $driver,
                    submodule: 'managing_members',
                    submoduleId: $driver->id
                );

                $this->importLogService->logSuccess('drivers', $this->fileName, $rowNumber, $row->toArray());
            } catch (\Exception $e) {
                Log::error('Driver Import Error: ' . $e->getMessage());
                $this->importLogService->logError('drivers', $this->fileName, $rowNumber, $e->getMessage(), $row->toArray());
            }
        }
    }
}
