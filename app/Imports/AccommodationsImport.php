<?php

namespace App\Imports;

use App\Models\Accommodation;
use App\Models\DropdownOption;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Http\Traits\HandlesUpdateConfirmation;
use App\Services\ImportLogService;
use Illuminate\Support\Facades\Log;

class AccommodationsImport implements ToCollection, WithHeadingRow
{
    use HandlesUpdateConfirmation;

    protected $importLogService;
    protected $fileName;

    public function __construct($fileName = 'accommodations.xlsx')
    {
        $this->importLogService = new ImportLogService();
        $this->fileName = $fileName;
        $this->importLogService->clearLogs('hotels');
    }

    public function collection(Collection $rows)
    {
        $currentEventId = session('current_event_id', getDefaultEventId() ?? null);
        $roomTypeOption = DropdownOption::whereHas('dropdown', function ($q) {
            $q->where('code', 'room_type');
        })->pluck('id')->toArray();

        $rowNumber = 1;

        foreach ($rows as $row) {
            $rowNumber++;

            try {
                $existingHotel = Accommodation::where('event_id', $currentEventId)
                                        ->where(function ($q) use ($row) {
                                            $q->where('hotel_name', trim($row['hotel_name_en']))
                                            ->orWhere('hotel_name_ar', trim($row['hotel_name_ar']));
                                        })
                                        ->first();

                if ($existingHotel) {
                    $this->importLogService->logError('hotels', $this->fileName, $rowNumber, 'Hotel already exists: ' . trim($row['hotel_name_en'] ?? $row['hotel_name_ar']), $row->toArray());
                    continue;
                }

                $accommodation = Accommodation::create([
                    'event_id'       => $currentEventId,
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
                            } else {
                                $this->importLogService->logError('hotels', $this->fileName, $rowNumber, 'Invalid room type ID: ' . $value, $row->toArray());
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

                $this->importLogService->logSuccess('hotels', $this->fileName, $rowNumber, $row->toArray());
            } catch (\Exception $e) {
                Log::error('Accommodation Import Error: ' . $e->getMessage());
                $this->importLogService->logError('hotels', $this->fileName, $rowNumber, $e->getMessage(), $row->toArray());
            }
        }
    }
}
