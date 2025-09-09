<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OtherInterviewMember extends Model
{
    protected $fillable = [
        'name_en',
        'name_ar',
        'status',
        'event_id',
    ];

    protected static function booted()
    {
        static::creating(function ($other_interview_member) {

            if (!$other_interview_member->event_id) {
                $sessionEventId = Session::get('current_event_id');
                if ($sessionEventId) {
                    $other_interview_member->event_id = $sessionEventId;
                } else {
                    $defaultEventId = getDefaultEventId();
                    $other_interview_member->event_id = $defaultEventId ? $defaultEventId : null;
                }
            }
        });
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
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
