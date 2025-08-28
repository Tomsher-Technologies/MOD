<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomAssignment extends Model
{
    protected $fillable = ['hotel_id', 'room_type_id', 'room_number', 'assigned_by', 'active_status','delegation_id'];

    public function delegation()
    {
        return $this->belongsTo(Delegation::class);
    }
    public function assignable()
    {
        return $this->morphTo();
    }

    public function roomType()
    {
        return $this->belongsTo(AccommodationRoom::class, 'room_type_id');
    }

    public function hotel()
    {
        return $this->belongsTo(Accommodation::class, 'hotel_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
