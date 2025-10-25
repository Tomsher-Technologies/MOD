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

class HotelExternalMembersSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $hotelId;
    protected $data;

    public function __construct($hotelId)
    {
        $this->hotelId = $hotelId;

        $this->data = ExternalMemberAssignment::with([
            'hotel',
            'roomType.roomType'
        ])
            ->where('hotel_id', $this->hotelId)
            ->where('active_status', 1)
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
        $hotelName = !empty($row->hotel->hotel_name) ? $row->hotel->hotel_name : $row->hotel->hotel_name_ar;

        return [
            '',
            $hotelName ?? '',
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
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1, 1);
                $sheet->setCellValue('A1', 'Hotel External Members Export - Exported on: ' . Carbon::now()->format('d-m-Y H:i A'));
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

                $sheet->getStyle('A1:L' . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1:L' . $lastRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            },
        ];
    }
}
