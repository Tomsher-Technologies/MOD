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

    public function getPhoneNumberWithoutCountryCodeAttribute()
    {
        if (!$this->phone_number) {
            return '';
        }

        $cleanNumber = preg_replace('/[^0-9]/', '', $this->phone_number);

        if (strlen($cleanNumber) === 12 && substr($cleanNumber, 0, 3) === '971') {
            return substr($cleanNumber, 3);
        }

        if (strlen($cleanNumber) === 9) {
            return $cleanNumber;
        }

        return $cleanNumber;
    }

    protected static function booted()
    {
        static::creating(function ($escort) {
            if (!$escort->event_id) {
                $sessionEventId = Session::get('current_event_id');
                if ($sessionEventId) {
                    $escort->event_id = $sessionEventId;
                } else {
                    $defaultEventId = getDefaultEventId();
                    $escort->event_id = $defaultEventId ? $defaultEventId : null;
                }
            }

            $eventId = $escort->event_id ?: Session::get('current_event_id') ?: getDefaultEventId();
            $event = Event::find($eventId);

            if ($event) {
                $latestEscort = self::where('event_id', $eventId)->latest('id')->first();
                $newId = $latestEscort ? $latestEscort->id + 1 : 1;

                $escort->code = $event->code . '-ES-' . str_pad($newId, 4, '0', STR_PAD_LEFT);
            } else {
                $latestEscort = self::latest('id')->first();
                $newId = $latestEscort ? $latestEscort->id + 1 : 1;

                $minLength = 3;
                $newIdLength = strlen((string)$newId);
                $padLength = $newIdLength > $minLength ? $newIdLength : $minLength;

                $escort->code = 'EC' . str_pad($newId, $padLength, '0', STR_PAD_LEFT);
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
                $q->where('code', 'rank');
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

        $arabicContent = trim($this->{$field . '_ar'});
        $englishContent = trim($this->{$field . '_en'});

        if ($lang === 'ar') {
            return !empty($arabicContent) ? $arabicContent : $englishContent;
        } else if ($lang === 'en') {
            return !empty($englishContent) ? $englishContent : $arabicContent;
        }

        return !empty($arabicContent) ? $arabicContent : $englishContent;
    }
}
