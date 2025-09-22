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
            // $existingEscort = Escort::where('code', trim($row['code']))
            //                     ->first();

            $escortData = [
                'name_en' => trim($row['name_en']) ?? null,
                'name_ar' => trim($row['name_ar']) ?? null,
                'military_number' => trim($row['military_number']) ?? null,
                'phone_number' => trim($row['phone_number']) ?? null,
                'email' => trim($row['email']) ?? null,
                'title_en' => trim($row['title_en']) ?? null,
                'title_ar' => trim($row['title_ar']) ?? null,
                'status' => 1,
            ];

            if (!empty($row['gender_id'])) {
                $gender = DropdownOption::whereHas('dropdown', function ($q) {
                    $q->where('code', 'gender');
                })->where('id', trim($row['gender_id']))->first();

                if ($gender) {
                    $escortData['gender_id'] = $gender->id;
                }
            }

            if (!empty($row['internal_ranking_id'])) {
                $ranking = DropdownOption::whereHas('dropdown', function ($q) {
                    $q->where('code', 'internal_ranking');
                })->where('id', trim($row['internal_ranking_id']))->first();

                if ($ranking) {
                    $escortData['internal_ranking_id'] = $ranking->id;
                }
            }

            if (!empty($row['unit_id'])) {
                $unit = DropdownOption::whereHas('dropdown', function ($q) {
                    $q->where('code', 'unit');
                })->where('id', trim($row['unit_id']))->first();

                if ($unit) {
                    $escortData['unit_id'] = $unit->id;
                }
            }

            // if (!empty($row['delegation_id'])) {
            //     $delegation = Delegation::where('id', trim($row['delegation_id']))->first();
            //     if ($delegation) {
            //         $escortData['delegation_id'] = $delegation->id;
            //     }
            // }

            if (!empty($row['spoken_languages_ids'])) {
                $languageIds = explode(',', trim($row['spoken_languages_ids']));
                $validLanguageIds = [];

                foreach ($languageIds as $languageId) {
                    $lang = DropdownOption::whereHas('dropdown', function ($q) {
                        $q->where('code', 'language');
                    })->where('id', trim($languageId))->first();

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

            // if ($existingEscort) {
            //     $existingEscort->update($escortData);

            //     $this->logActivity(
            //         module: 'Escorts',
            //         action: 'update-excel',
            //         model: $existingEscort,
            //         submodule: 'managing_members',
            //         submoduleId: $existingEscort->id
            //     );
            // } else {
            $escort = Escort::create($escortData);

            $this->logActivity(
                module: 'Escorts',
                action: 'create-excel',
                model: $escort,
                submodule: 'managing_members',
                submoduleId: $escort->id
            );
            // }
        }
    }
}
