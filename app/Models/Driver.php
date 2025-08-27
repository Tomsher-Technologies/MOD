<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'military_number',
        'title',
        'name_ar',
        'name_en',
        'phone_number',
        'driver_id',
        'unit_id',
        'car_type',
        'car_number',
        'capacity',
        'note1',
        'status',
        'delegation_id',
        'event_id',
    ];

    protected static function booted()
    {
        static::creating(function ($driver) {
            $latestDriver = self::latest('id')->first();
            $newId = $latestDriver ? $latestDriver->id + 1 : 1;
            $driver->code = 'DR' . str_pad($newId, 3, '0', STR_PAD_LEFT);


            if (!$driver->event_id) {
                $sessionEventId = Session::get('current_event_id');
                if ($sessionEventId) {
                    $driver->event_id = $sessionEventId;
                } else {
                    $defaultEventId = getDefaultEventId();
                    $driver->event_id = $defaultEventId ? $defaultEventId : null;
                }
            }
        });
    }

    public function unit()
    {
        return $this->belongsTo(DropdownOption::class, 'unit_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'unit');
            });
    }

    public function delegations()
    {
        return $this->belongsToMany(Delegation::class, 'delegation_drivers', 'driver_id', 'delegation_id')
            ->withPivot('status', 'assigned_by', 'start_date', 'end_date')
            ->withTimestamps();
    }
}
