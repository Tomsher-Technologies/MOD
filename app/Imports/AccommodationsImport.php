<?php

namespace App\Imports;

use App\Models\Accommodation;
use App\Models\DropdownOption;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Http\Traits\HandlesUpdateConfirmation;

class AccommodationsImport implements ToCollection, WithHeadingRow
{
    use HandlesUpdateConfirmation;
    public function collection(Collection $rows)
    {
        $roomTypeOption = DropdownOption::whereHas('dropdown', function($q){
                                $q->where('code', 'room_type');
                            })->pluck('id')->toArray();

        foreach ($rows as $row) {
            $existingHotel = Accommodation::where('hotel_name', trim($row['hotel_name_en']))
                                        ->orWhere('hotel_name_ar', trim($row['hotel_name_ar']))
                                        ->first();

            if ($existingHotel) {
                continue;
            }

            $accommodation = Accommodation::create([
                'hotel_name'     => trim($row['hotel_name_en']) ?? null,
                'hotel_name_ar'  => trim($row['hotel_name_ar']) ?? null,
                'contact_number' => $row['contact_number'] ?? null,
                'address'        => $row['address'] ?? null,
            ]);

            foreach ($row as $column => $value) {
                // Rooms
                if (str_starts_with($column, 'room_type_')) {
                    $index = str_replace('room_type_', '', $column);
                    $totalRoomsCol = 'total_rooms_' . $index;
                    if (!empty($value) && !empty($row[$totalRoomsCol])) {
                        if (in_array($value, $roomTypeOption)) {
                            $accommodation->rooms()->create([
                                'room_type'   => $value,
                                'total_rooms' => $row[$totalRoomsCol],
                            ]);
                        }
                    }
                }

                // Contacts
                if (str_starts_with($column, 'contact_name_')) {
                    $index = str_replace('contact_name_', '', $column);
                    $phoneCol = 'contact_phone_' . $index;
                    if (!empty($value) || !empty($row[$phoneCol])) {
                        $accommodation->contacts()->create([
                            'name'  => $value,
                            'phone' => $row[$phoneCol],
                        ]);
                    }
                }
            }

            $this->logActivity(
                module: 'Accommodations',
                action: 'create-excel',
                model: $accommodation,
                submodule: 'managing_members',
                submoduleId: $accommodation->id
            );
        }
    }
}
