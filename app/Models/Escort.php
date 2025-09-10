<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class Escort extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'event_id',
        'name_ar',
        'name_en',
        'military_number',
        'delegation_id',
        'gender_id',
        'spoken_languages',
        'internal_ranking_id',
        'status',
        'title_en',
        'title_ar',
        'unit_id',
        'rank',
        'phone_number',
        'email',
        'nationality_id',
        'date_of_birth',
        'current_room_assignment_id'
    ];

    protected $casts = [
        'spoken_languages' => 'array',
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
        static::creating(function ($escort) {
            $latestEscort = self::latest('id')->first();
            $newId = $latestEscort ? $latestEscort->id + 1 : 1;
            $escort->code = 'EC' . str_pad($newId, 3, '0', STR_PAD_LEFT);

            if (!$escort->event_id) {
                $sessionEventId = Session::get('current_event_id');
                if ($sessionEventId) {
                    $escort->event_id = $sessionEventId;
                } else {
                    $defaultEventId = getDefaultEventId();
                    $escort->event_id = $defaultEventId ? $defaultEventId : null;
                }
            }
        });
    }

    public function getSpokenLanguagesLabelsAttribute()
    {
        if (empty($this->spoken_languages)) {
            return null;
        }
        return DropdownOption::whereIn('id', $this->spoken_languages)->pluck('value')->implode(', ');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function delegation()
    {
        return $this->belongsTo(Delegation::class);
    }

    public function gender()
    {
        return $this->belongsTo(DropdownOption::class, 'gender_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'gender');
            });
    }
    public function unit()
    {
        return $this->belongsTo(DropdownOption::class, 'unit_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'unit');
            });
    }
    public function internalRanking()
    {
        return $this->belongsTo(DropdownOption::class, 'internal_ranking_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'internal_ranking');
            });
    }

    public function nationality()
    {
        return $this->belongsTo(DropdownOption::class, 'nationality_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'nationality');
            });
    }

    public function delegations()
    {
        return $this->belongsToMany(Delegation::class, 'delegation_escorts', 'escort_id', 'delegation_id')->withPivot('status', 'assigned_by');
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
