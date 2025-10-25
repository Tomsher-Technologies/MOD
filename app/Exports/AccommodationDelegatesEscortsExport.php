<?php

namespace App\Exports;

use App\Models\Delegation;
use App\Models\RoomAssignment;
use App\Models\Delegate;
use App\Models\Escort;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AccommodationDelegatesEscortsExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $sheets = [];
        
        $sheets[] = new AccommodationDelegatesEscortsSheet();
        
        $sheets[] = new ExternalMembersSheet();
        
        return $sheets;
    }
}