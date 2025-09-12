<?php

namespace App\Imports;

use App\Models\Escort;
use App\Models\DropdownOption;
use App\Models\Delegation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Http\Traits\HandlesUpdateConfirmation;

class EscortImport implements ToCollection, WithHeadingRow
{
    use HandlesUpdateConfirmation;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $existingEscort = Escort::where('code', trim($row['code']))
                                ->first();

            $escortData = [
                'name_en' => trim($row['name_en']) ?? null,
                'name_ar' => trim($row['name_ar']) ?? null,
                'military_number' => trim($row['military_number']) ?? null,
                'phone_number' => trim($row['phone_number']) ?? null,
                'email' => trim($row['email']) ?? null,
                'title_en' => trim($row['title_en']) ?? null,
                'title_ar' => trim($row['title_ar']) ?? null,
                'date_of_birth' => !empty($row['date_of_birth']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_of_birth'])->format('Y-m-d') : null,
                'status' => $row['status'] ?? 1,
            ];

            if (!empty($row['gender'])) {
                $gender = DropdownOption::whereHas('dropdown', function($q) {
                    $q->where('code', 'gender');
                })->where('value', trim($row['gender']))->first();
                
                if ($gender) {
                    $escortData['gender_id'] = $gender->id;
                }
            }

            if (!empty($row['nationality'])) {
                $nationality = DropdownOption::whereHas('dropdown', function($q) {
                    $q->where('code', 'nationality');
                })->where('value', trim($row['nationality']))->first();
                
                if ($nationality) {
                    $escortData['nationality_id'] = $nationality->id;
                }
            }

            if (!empty($row['internal_ranking'])) {
                $ranking = DropdownOption::whereHas('dropdown', function($q) {
                    $q->where('code', 'internal_ranking');
                })->where('value', trim($row['internal_ranking']))->first();
                
                if ($ranking) {
                    $escortData['internal_ranking_id'] = $ranking->id;
                }
            }

            if (!empty($row['unit'])) {
                $unit = DropdownOption::whereHas('dropdown', function($q) {
                    $q->where('code', 'unit');
                })->where('value', trim($row['unit']))->first();
                
                if ($unit) {
                    $escortData['unit_id'] = $unit->id;
                }
            }

            // if (!empty($row['delegation_code'])) {
            //     $delegation = Delegation::where('code', trim($row['delegation_code']))->first();
            //     if ($delegation) {
            //         $escortData['delegation_id'] = $delegation->id;
            //     }
            // }

            if (!empty($row['spoken_languages'])) {
                $languages = explode(',', trim($row['spoken_languages']));
                $languageIds = [];
                
                foreach ($languages as $language) {
                    $lang = DropdownOption::whereHas('dropdown', function($q) {
                        $q->where('code', 'language');
                    })->where('value', trim($language))->first();
                    
                    if ($lang) {
                        $languageIds[] = $lang->id;
                    }
                }
                
                if (!empty($languageIds)) {
                    $escortData['spoken_languages'] = implode(',', $languageIds);
                }
            }

            if (isset($escortData['phone_number']) && !empty($escortData['phone_number'])) {
                $phoneNumber = preg_replace('/[^0-9]/', '', $escortData['phone_number']);
                if (strlen($phoneNumber) === 9) {
                    $escortData['phone_number'] = '971' . $phoneNumber;
                }
            }

            if ($existingEscort) {
                $existingEscort->update($escortData);
                
                $this->logActivity(
                    module: 'Escorts',
                    action: 'update-excel',
                    model: $existingEscort,
                    submodule: 'managing_members',
                    submoduleId: $existingEscort->id
                );
            } else {
                $escort = Escort::create($escortData);
                
                $this->logActivity(
                    module: 'Escorts',
                    action: 'create-excel',
                    model: $escort,
                    submodule: 'managing_members',
                    submoduleId: $escort->id
                );
            }
        }
    }
}