<?php

namespace App\Exports;

use App\Models\Delegation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class DelegationExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus'
        ])->get()->map(function ($delegation) {
            return [
                'Code' => $delegation->code,
                'Invitation From' => $delegation->invitationFrom->value ?? '',
                'Continent' => $delegation->continent->value ?? '',
                'Country' => $delegation->country->name ?? '',
                'Invitation Status' => $delegation->invitationStatus->value ?? '',
                'Participation Status' => $delegation->participationStatus->value ?? '',
                'Note 1' => $delegation->note1 ?? '',
                'Note 2' => $delegation->note2 ?? '',
                'Created At' => $delegation->created_at ? $delegation->created_at->format('Y-m-d H:i:s') : '',
                'Updated At' => $delegation->updated_at ? $delegation->updated_at->format('Y-m-d H:i:s') : '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Code',
            'Invitation From',
            'Continent',
            'Country',
            'Invitation Status',
            'Participation Status',
            'Note 1',
            'Note 2',
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
                $sheet->setCellValue('A1', 'Delegations Export - Exported on: '. Carbon::now()->format('d-m-Y H:i A'));
                $sheet->mergeCells('A1:J1');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A2:J2')->getFont()->setBold(true);
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(15);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getColumnDimension('G')->setWidth(25);
                $sheet->getColumnDimension('H')->setWidth(25);
                $sheet->getColumnDimension('I')->setWidth(20);
                $sheet->getColumnDimension('J')->setWidth(20);
            },
        ];
    }
}