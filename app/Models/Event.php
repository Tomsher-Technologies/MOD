<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
