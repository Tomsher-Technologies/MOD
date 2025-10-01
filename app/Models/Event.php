<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
        'name_en',
        'code',
        'name_ar',
        'logo',
        'image',
        'start_date',
        'end_date',
        'status',
        'is_default',
    ];

    protected static function booted()
    {
        static::creating(function ($event) {
            if (empty($event->code)) {
                $event->code = $event->generateEventCodeBasedOnName();
            }
        });
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function assignedUsers()
    {
        return $this->hasMany(EventUserRole::class);
    }

    public function interviewMembers()
    {
        return $this->hasMany(InterviewMember::class);
    }

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? getActiveLanguage() : $lang;

        if ($lang !== 'en') {
            $field = $field.'_ar';
        }else{
            $field = $field.'_en';
        }

        return $this->$field;
    }


    public function generateEventCode()
    {
        $lastEvent = Event::orderBy('created_at', 'desc')->first();

        if (!$lastEvent || !$lastEvent->code) {
            return 'EVT0001';
        }

        $lastNumber = (int) substr($lastEvent->code, 3);

        $newNumber = $lastNumber + 1;

        return 'EVT' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function generateEventCodeBasedOnName()
    {
        $eventName = $this->getTranslation('name');
        $eventParts = explode(' ', $eventName);

        if (count($eventParts) >= 2) {
            $prefix = strtoupper(substr($eventParts[0], 0, 1) . substr($eventParts[1], 0, 1));
        } else {
            $prefix = strtoupper(substr($eventParts[0], 0, 2));
        }

        $startDate = $this->start_date;
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        } else {
            $startDate = Carbon::instance($startDate);
        }
        $year = substr($startDate->format('Y'), -2);

        $baseCode = $prefix . '' . $year;
        $existingEvent = Event::where('code', $baseCode)->first();

        if ($existingEvent) {
            $sequence = 1;
            do {
                $newCode = $prefix . '' . $sequence . '' . $year;
                $existingEvent = Event::where('code', $newCode)->first();
                if ($existingEvent) {
                    $sequence++;
                }
            } while ($existingEvent);
            return $newCode;
        }

        return $baseCode;
    }
}
