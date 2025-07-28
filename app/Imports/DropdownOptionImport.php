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
        $dropdown = Dropdown::where('code', $row['code'])->first();

        if (!$dropdown) {
            return null; 
        }

        $value = trim($row['value']);

        // Check if it already exists
        $existing = DropdownOption::where('dropdown_id', $dropdown->id)
                        ->where('value', $value)
                        ->first();

        if ($existing) {
            $existing->update([
                'status'     => $row['status'] ?? $existing->status,
                'sort_order' => $row['sort_order'] ?? $existing->sort_order,
            ]);
            return null;
        }

        // Create new
        return new DropdownOption([
            'dropdown_id' => $dropdown->id,
            'value'       => $value,
            'sort_order'  => $row['sort_order'] ?? 0,
            'status'      => $row['status'] ?? 1,
        ]);
    }
}
