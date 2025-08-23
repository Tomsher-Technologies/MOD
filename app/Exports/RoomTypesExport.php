<?php

namespace App\Exports;

use App\Models\DropdownOption;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;


class RoomTypesExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return DropdownOption::whereHas('dropdown', function($q){
            $q->where('code', 'room_type');
        })->get(['id', 'value']); 
    }

    public function headings(): array
    {
        return [
            'ID',
            'Room Type',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Bold first row
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                $sheet->insertNewRowBefore(1, 1); 
                $sheet->setCellValue('A1', 'Exported on: '. \Carbon\Carbon::now()->format('d-m-Y H:i A'));
                $sheet->mergeCells('A1:B1');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A2:B2')->getFont()->setBold(true);
                $sheet->getColumnDimension('A')->setWidth(10);
                $sheet->getColumnDimension('B')->setWidth(40);
            },
        ];
    }
}

