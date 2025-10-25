<?php

namespace App\Exports;

use App\Models\RoomAssignment;
use App\Models\Delegate;
use App\Models\Escort;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class HotelAssignmentsSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $hotelId;
    protected $data;

    public function __construct($hotelId)
    {
        $this->hotelId = $hotelId;

        $delegates = RoomAssignment::with([
            'assignable',
            'assignable.delegateTransports',
            'delegation',
            'delegation.country',
            'delegation.invitationFrom',
            'hotel',
            'roomType.roomType'
        ])
            ->where('hotel_id', $this->hotelId)
            ->where('active_status', 1)
            ->where('assignable_type', Delegate::class)
            ->get();

        $escorts = RoomAssignment::with([
            'assignable',
            'delegation',
            'delegation.country',
            'delegation.invitationFrom',
            'hotel',
            'roomType.roomType'
        ])
            ->where('hotel_id', $this->hotelId)
            ->where('active_status', 1)
            ->where('assignable_type', Escort::class)
            ->get();

        $combined = $delegates->concat($escorts);

        $this->data = $combined->sortBy([
            ['delegation.country.sort_order', 'asc'],
            ['delegation.invitationFrom.sort_order', 'asc']
        ]);
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Serial Number',
            'Delegation Code',
            'Hotel Name',
            'Country',
            'Invitation From',
            'Title and Name',
            'Position',
            'Arrival Date',
            'Departure Date',
            'Room Number',
            'Room Type',
            'Type'
        ];
    }

    public function map($row): array
    {
        $isEscort = $row->assignable_type === 'App\Models\Escort';
        $member = $row->assignable;
        $delegation = $row->delegation;

        if ($isEscort) {
            $name = $member->getTranslation('name', 'en') ?? '';
            $title = $member->getTranslation('title') ?? '';
        } else {
            $name = $member->getTranslation('name', 'en') ?? '';
            $title = $member->getTranslation('title', 'en') ?? '';
        }

        $titleAndName = trim($title . ' ' . $name);

        if ($isEscort) {
            $position = $member->getTranslation('designation', 'en') ?? '';
        } else {
            $position = $member->getTranslation('designation', 'en') ?? '';
        }

        $arrival_date = '';
        $departure_date = '';

        if (!$isEscort) {
            $arrival = $member->delegateTransports()->where('type', 'arrival')->latest('date_time')->first();
            $departure = $member->delegateTransports()->where('type', 'departure')->latest('date_time')->first();

            $arrival_date = $arrival ? $arrival->date_time : '';
            $departure_date = $departure ? $departure->date_time : '';
        }

        $countryName = !empty($delegation->country->name) ? $delegation->country->getNameEn() : $delegation->country->getNameAr();
        $invitationFromValue = !empty($delegation->invitationFrom->getValueEn()) ? $delegation->invitationFrom->getValueEn() : $delegation->invitationFrom->getNameAr();


        return [
            '',
            $delegation->code ?? '',
            $row->hotel->getHotelNameTranslation('en') ?? '',
            $countryName ?? '',
            $invitationFromValue ?? '',
            $titleAndName,
            $position,
            $arrival_date ? (new Carbon($arrival_date))->format('Y-m-d') : '',
            $departure_date ? (new Carbon($departure_date))->format('Y-m-d') : '',
            $row->room_number ?? '',
            $row->roomType->roomType->value ?? '',
            $isEscort ? 'Escort' : 'Delegate'
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
                $sheet->setCellValue('A1', 'Hotel Assignments Export - Exported on: ' . Carbon::now()->format('d-m-Y H:i A'));
                $sheet->mergeCells('A1:L1');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A2:L2')->getFont()->setBold(true);

                $sheet->getColumnDimension('A')->setWidth(12);
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(30);
                $sheet->getColumnDimension('G')->setWidth(25);
                $sheet->getColumnDimension('H')->setWidth(12);
                $sheet->getColumnDimension('I')->setWidth(12);
                $sheet->getColumnDimension('J')->setWidth(15);
                $sheet->getColumnDimension('K')->setWidth(15);
                $sheet->getColumnDimension('L')->setWidth(12);

                $lastRow = $sheet->getHighestRow();
                for ($row = 3; $row <= $lastRow; $row++) {
                    $sheet->setCellValue('A' . $row, $row - 2);
                }

                for ($row = 3; $row <= $lastRow; $row++) {
                    $type = $sheet->getCell('L' . $row)->getValue();
                    if ($type === 'Escort') {
                        $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => 'solid',
                                'startColor' => ['rgb' => 'E6F3FF'] 
                            ]
                        ]);
                    }
                }
            },
        ];
    }
}
