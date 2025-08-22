<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
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
        'status',
        'delegation_id',
        'event_id',
    ];

    protected static function booted()
    {
        static::creating(function ($driver) {
            $latestDriver = self::withTrashed()->latest('id')->first();
            $newId = $latestDriver ? $latestDriver->id + 1 : 1;
            $driver->code = 'DR' . str_pad($newId, 3, '0', STR_PAD_LEFT);
        });
    }

    public function delegations()
    {
        return $this->belongsToMany(Delegation::class, 'delegation_drivers', 'driver_id', 'delegation_id')->withPivot('status');
    }
}
