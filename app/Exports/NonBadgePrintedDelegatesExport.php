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

class NonBadgePrintedDelegatesExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $delegates;

    public function __construct($delegates)
    {
        $this->delegates = $delegates;
    }

    public function collection()
    {
        return $this->delegates->map(function ($delegate) {
            return [
                'Delegate Code' => $delegate->code,
                'Delegate Name' => $delegate->name_en,
                'Delegation Code' => $delegate->delegation->code,
                'Country' => $delegate->delegation->country->name ?? '',
                'Continent' => $delegate->delegation->continent->value ?? '',
                'Invitation From' => $delegate->delegation->invitationFrom->value ?? '',
                'Title En' => $delegate->title_en ?? '',
                'Title Ar' => $delegate->title_ar ?? '',
                'Designation' => $delegate->designation_en,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Delegate Code',
            'Delegate Name',
            'Delegation Code',
            'Country',
            'Continent',
            'Invitation From',
            'Title En',
            'Title Ar',
            'Designation',
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
                $sheet->setCellValue('A1', 'Exported on: '. Carbon::now()->format('d-m-Y H:i A'));
                $sheet->mergeCells('A1:H1');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A2:H2')->getFont()->setBold(true);
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(25);
                $sheet->getColumnDimension('C')->setWidth(15);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getColumnDimension('G')->setWidth(15);
                $sheet->getColumnDimension('H')->setWidth(15);
                $sheet->getColumnDimension('I')->setWidth(25);
            },
        ];
    }
}