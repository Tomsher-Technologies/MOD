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
        'title_en',
        'title_ar',
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
        'current_room_assignment_id'
    ];

    public function getNameAttribute()
    {
        $lang = getActiveLanguage();

        if ($lang !== 'en' && !empty($this->attributes['name_ar'])) {
            return $this->attributes['name_ar'];
        }

        return $this->attributes['name_en'] ?? '';
    }

    protected static function booted()
    {
        static::creating(function ($driver) {
            $latestDriver = self::latest('id')->first();
            $newId = $latestDriver ? $latestDriver->id + 1 : 1;

            $minLength = 3;
            $newIdLength = strlen((string)$newId);
            $padLength = $newIdLength > $minLength ? $newIdLength : $minLength;

            $driver->code = 'DR' . str_pad($newId, $padLength, '0', STR_PAD_LEFT);

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

    public function getPhoneNumberWithoutCountryCodeAttribute()
    {
        if (!$this->phone_number) {
            return '';
        }

        $cleanNumber = preg_replace('/[^0-9]/', '', $this->phone_number);

        // If it starts with 971 and is 12 digits, remove the country code
        if (strlen($cleanNumber) === 12 && substr($cleanNumber, 0, 3) === '971') {
            return substr($cleanNumber, 3);
        }

        // If it's already 9 digits, return as is
        if (strlen($cleanNumber) === 9) {
            return $cleanNumber;
        }

        return $cleanNumber;
    }

    public function title()
    {
        return $this->belongsTo(DropdownOption::class, 'title_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'title');
            });
    }

    public function title_value()
    {
        return $this->belongsTo(DropdownOption::class, 'title_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'title');
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

    public function roomAssignments()
    {
        return $this->morphMany(RoomAssignment::class, 'assignable');
    }

    public function currentRoomAssignment()
    {
        return $this->belongsTo(RoomAssignment::class, 'current_room_assignment_id');
    }

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? getActiveLanguage() : $lang;

        if ($lang !== 'en') {
            $field =  $field . '_ar';
        } else {
            $field =  $field . '_en';
        }

        return $this->$field;
    }
}
