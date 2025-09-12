<?php

namespace App\Imports;

use App\Models\Driver;
use App\Models\DropdownOption;
use App\Models\Delegation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Http\Traits\HandlesUpdateConfirmation;

class DriverImport implements ToCollection, WithHeadingRow
{
    use HandlesUpdateConfirmation;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $existingDriver = Driver::where('code', trim($row['code']))
                                ->first();

            $driverData = [
                'name_en' => trim($row['name_en']) ?? null,
                'name_ar' => trim($row['name_ar']) ?? null,
                'military_number' => trim($row['military_number']) ?? null,
                'phone_number' => trim($row['phone_number']) ?? null,
                'driver_id' => trim($row['driver_id']) ?? null,
                'car_type' => trim($row['car_type']) ?? null,
                'car_number' => trim($row['car_number']) ?? null,
                'capacity' => trim($row['capacity']) ?? null,
                'note1' => trim($row['note1']) ?? null,
                'title_en' => trim($row['title_en']) ?? null,
                'title_ar' => trim($row['title_ar']) ?? null,
                'status' => $row['status'] ?? 1,
            ];

            if (!empty($row['unit'])) {
                $unit = DropdownOption::whereHas('dropdown', function($q) {
                    $q->where('code', 'unit');
                })->where('value', trim($row['unit']))->first();
                
                if ($unit) {
                    $driverData['unit_id'] = $unit->id;
                }
            }

            // if (!empty($row['delegation_code'])) {
            //     $delegation = Delegation::where('code', trim($row['delegation_code']))->first();
            //     if ($delegation) {
            //         $driverData['delegation_id'] = $delegation->id;
            //     }
            // }

            if (isset($driverData['phone_number']) && !empty($driverData['phone_number'])) {
                $phoneNumber = preg_replace('/[^0-9]/', '', $driverData['phone_number']);
                if (strlen($phoneNumber) === 9) {
                    $driverData['phone_number'] = '971' . $phoneNumber;
                }
            }

            if ($existingDriver) {
                $existingDriver->update($driverData);
                
                $this->logActivity(
                    module: 'Drivers',
                    action: 'update-excel',
                    model: $existingDriver,
                    submodule: 'managing_members',
                    submoduleId: $existingDriver->id
                );
            } else {
                $driver = Driver::create($driverData);

                $this->logActivity(
                    module: 'Drivers',
                    action: 'create-excel',
                    model: $driver,
                    submodule: 'managing_members',
                    submoduleId: $driver->id
                );
            }
        }
    }
}