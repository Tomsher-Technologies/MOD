<?php

namespace App\Exports;

use App\Models\Delegate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class DelegateExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return Delegate::with([
            'delegation',
            'gender',
            'parent',
            'relationship',
            'internalRanking'
        ])->get()->map(function ($delegate) {
            return [
                'Delegation Code' => $delegate->delegation->code ?? '',
                'Code' => $delegate->code,
                'Name (EN)' => $delegate->name_en ?? '',
                'Name (AR)' => $delegate->name_ar ?? '',
                'Title (EN)' => $delegate->title_en ?? '',
                'Title (AR)' => $delegate->title_ar ?? '',
                'Designation (EN)' => $delegate->designation_en ?? '',
                'Designation (AR)' => $delegate->designation_ar ?? '',
                'Gender' => $delegate->gender->value ?? '',
                'Country' => $delegate->delegation->country->name ?? '',
                'Continent' => $delegate->delegation->continent->value ?? '',
                'Parent Name' => $delegate->parent->name_en ?? '',
                'Relationship' => $delegate->relationship->value ?? '',
                'Internal Ranking' => $delegate->internalRanking->value ?? '',
                'Team Head' => $delegate->team_head ? 'Yes' : 'No',
                'Badge Printed' => $delegate->badge_printed ? 'Yes' : 'No',
                'Accommodation' => $delegate->accommodation ? 'Yes' : 'No',
                'Note' => $delegate->note ?? '',
                'Created At' => $delegate->created_at ? $delegate->created_at->format('Y-m-d H:i:s') : '',
                'Updated At' => $delegate->updated_at ? $delegate->updated_at->format('Y-m-d H:i:s') : '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Delegation Code',
            'Code',
            'Name (EN)',
            'Name (AR)',
            'Title (EN)',
            'Title (AR)',
            'Designation (EN)',
            'Designation (AR)',
            'Gender',
            'Country',
            'Continent',
            'Parent Name',
            'Relationship',
            'Internal Ranking',
            'Team Head',
            'Badge Printed',
            'Accommodation',
            'Note',
            'Created At',
            'Updated At',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                $sheet->insertNewRowBefore(1, 1);
                $sheet->setCellValue('A1', 'Delegates Export - Exported on: '. Carbon::now()->format('d-m-Y H:i A'));
                $sheet->mergeCells('A1:T1');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A2:T2')->getFont()->setBold(true);
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(25);
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(25);
                $sheet->getColumnDimension('G')->setWidth(25);
                $sheet->getColumnDimension('H')->setWidth(15);
                $sheet->getColumnDimension('I')->setWidth(15);
                $sheet->getColumnDimension('J')->setWidth(20);
                $sheet->getColumnDimension('K')->setWidth(15);
                $sheet->getColumnDimension('L')->setWidth(25);
                $sheet->getColumnDimension('M')->setWidth(15);
                $sheet->getColumnDimension('N')->setWidth(15);
                $sheet->getColumnDimension('O')->setWidth(12);
                $sheet->getColumnDimension('P')->setWidth(12);
                $sheet->getColumnDimension('Q')->setWidth(12);
                $sheet->getColumnDimension('R')->setWidth(25);
                $sheet->getColumnDimension('S')->setWidth(20);
                $sheet->getColumnDimension('T')->setWidth(20);
            },
        ];
    }
}