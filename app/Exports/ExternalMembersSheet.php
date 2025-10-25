<?php

namespace App\Exports;

use App\Models\ExternalMemberAssignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ExternalMembersSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $data;
    
    public function __construct()
    {
        $currentEventId = session('current_event_id', getDefaultEventId());
        
        $this->data = ExternalMemberAssignment::with([
            'hotel',
            'roomType.roomType'
        ])
        ->where('active_status', 1)
        ->whereHas('hotel', function($q) use ($currentEventId) {
            $q->where('event_id', $currentEventId);
        })
        ->get();
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Serial Number',
            'Hotel Name',
            'Name',
            'Coming From',
            'Room Type',
            'Room Number'
        ];
    }

    public function map($row): array
    {
        return [
            '',
            $row->hotel->getHotelNameTranslation('en') ?? '',
            $row->name ?? '',
            $row->coming_from ?? '',
            $row->roomType->roomType->value ?? '',
            $row->room_number ?? ''
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
                $sheet->setCellValue('A1', 'External Members Export - Exported on: '. Carbon::now()->format('d-m-Y H:i A'));
                $sheet->mergeCells('A1:F1');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A2:F2')->getFont()->setBold(true);
                
                $sheet->getColumnDimension('A')->setWidth(12);
                $sheet->getColumnDimension('B')->setWidth(25);
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(15);
                
                $lastRow = $sheet->getHighestRow();
                for ($row = 3; $row <= $lastRow; $row++) {
                    $sheet->setCellValue('A' . $row, $row - 2);
                }
            },
        ];
    }
}