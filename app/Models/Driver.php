<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'military_number',
        'title',
        'name_ar',
        'name_en',
        'mobile_number',
        'driver_id',
        'car_type',
        'car_number',
        'capacity',
        'note1',
        'delegation_id',
        'event_id',
    ];

    public function delegations()
    {
        return $this->belongsToMany(Delegation::class, 'delegation_drivers', 'driver_id', 'delegation_id')->withPivot('status');
    }
}
