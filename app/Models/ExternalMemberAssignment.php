<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalMemberAssignment extends Model
{
    use HasFactory;

    protected $table = 'external_member_assignments';

    protected $fillable = [
        'name',
        'hotel_id',
        'room_type_id',
        'room_number',
        'active_status',
        'assigned_by',
    ];

    /**
     * Relationships
     */

    // Hotel relation
    public function hotel()
    {
        return $this->belongsTo(Accommodation::class, 'hotel_id');
    }

    // Room type relation
    public function roomType()
    {
        return $this->belongsTo(AccommodationRoom::class, 'room_type_id');
    }

    // User who assigned
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
