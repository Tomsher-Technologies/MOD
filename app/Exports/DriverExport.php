<?php

namespace App\Exports;

use App\Models\Driver;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DriverExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        $currentEventId = session('current_event_id', getDefaultEventId());
        
        return Driver::with(['delegations', 'title', 'unit'])
            ->where('event_id', $currentEventId)
            ->where('status', 1)
            ->get();
    }

    public function headings(): array
    {
        return [
            __db('code'),
            __db('military_number'),
            __db('name_en'),
            __db('name_ar'),
            __db('title_en'),
            __db('title_ar'),
            __db('unit'),
            __db('phone_number'),
            __db('car_type'),
            __db('car_number'),
            __db('capacity'),
            __db('note1'),
            __db('delegation_code'),
        ];
    }

    public function map($driver): array
    {
        $activeDelegation = $driver->delegations()->wherePivot('status', 1)->first();
        
        return [
            $driver->code,
            $driver->military_number,
            $driver->name_en,
            $driver->name_ar,
            $driver->title_en,
            $driver->title_ar,
            $driver->unit ? $driver->unit->value : '',
            $driver->phone_number,
            $driver->car_type,
            $driver->car_number,
            $driver->capacity,
            $driver->note1,
            $activeDelegation ? $activeDelegation->code : '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]] 
        ];
    }
}