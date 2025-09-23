<?php

namespace App\Exports;

use App\Models\Country;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CountriesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Country::with('continent')->orderBy('sort_order')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name (English)',
            'Name (Arabic)',
            'Short Code',
            'Continent',
            'Sort Order',
            'Status',
            'Created At'
        ];
    }

    public function map($country): array
    {
        return [
            $country->id,
            $country->getNameEn(),
            $country->getNameAr() ?? '',
            $country->short_code,
            $country->continent->value ?? '',
            $country->sort_order ?? 0,
            $country->status ? 'Active' : 'Inactive',
            $country->created_at->format('Y-m-d H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}