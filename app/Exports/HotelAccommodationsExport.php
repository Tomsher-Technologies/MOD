<?php

namespace App\Exports;

use App\Models\RoomAssignment;
use App\Models\ExternalMemberAssignment;
use App\Models\Delegate;
use App\Models\Escort;
use App\Models\Driver;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class HotelAccommodationsExport implements WithMultipleSheets
{
    use Exportable;

    protected $hotelId;

    public function __construct($hotelId)
    {
        $this->hotelId = $hotelId;
    }

    public function sheets(): array
    {
        $sheets = [];
        
        $sheets[] = new HotelAssignmentsSheet($this->hotelId);
        
        $sheets[] = new HotelExternalMembersSheet($this->hotelId);
        
        return $sheets;
    }
}