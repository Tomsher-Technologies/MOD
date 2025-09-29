<?php

namespace App\Imports;

use App\Models\Dropdown;
use App\Models\DropdownOption;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DropdownOptionImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $dropdown = Dropdown::where('code', $row['dropdown_code'])->first();

        if (!$dropdown) {
            return null;
        }

        $value = trim($row['value_en']);
        $valueAr = trim($row['value_ar']);

        if(($value != NULL || $value != '') || ($valueAr != NULL || $valueAr != '')){ 
            $query = DropdownOption::where('dropdown_id', $dropdown->id);

            if (!empty($value)) {
                $query->where('value', $value);
            } elseif (!empty($valueAr)) {
                $query->where('value_ar', $valueAr);
            }

            $existing = $query->first();

            if ($existing) {
                $existing->update([
                    'status'     => $row['status'] ?? $existing->status,
                    'code'       => $row['code'] ?? $existing->code,
                    'value_ar'    => $valueAr ?? null,
                    'sort_order'  => $row['sort_order'] ?? 0,
                    'status'      => $row['status'] ?? 1,
                ]);
                return null;
            }

            // Create new
            return new DropdownOption([
                'dropdown_id' => $dropdown->id,
                'value'       => $value,
                'code'        => $row['code'] ?? null,
                'value_ar'    => $valueAr ?? null,
                'sort_order'  => $row['sort_order'] ?? 0,
                'status'      => $row['status'] ?? 1,
            ]);
        }
    }
}
