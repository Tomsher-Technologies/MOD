<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccommodationRoom extends Model
{
    protected $fillable = ['accommodation_id', 'room_type', 'total_rooms'];

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function roomType()
    {
        return $this->belongsTo(DropdownOption::class, 'room_type')
            ->whereHas('dropdown', function ($query) {
                $query->where('code', 'room_type');
            });
    }

}
