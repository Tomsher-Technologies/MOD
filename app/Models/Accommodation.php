<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    protected $fillable = ['hotel_name', 'hotel_name_ar', 'address', 'contact_number', 'status', 'event_id', 'ref_code', 'created_by', 'updated_by'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function rooms()
    {
        return $this->hasMany(AccommodationRoom::class);
    }

    public function contacts()
    {
        return $this->hasMany(AccommodationContact::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function generateRefCode()
    {
        $lastAcc = Accommodation::orderBy('created_at', 'desc')->first();

        if (!$lastAcc || !$lastAcc->ref_code) {
            return 'ACC0001';
        }

        $lastNumber = (int) substr($lastAcc->ref_code, 3);

        $newNumber = $lastNumber + 1;

        return 'ACC' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    protected static function booted()
    {
        static::creating(function ($accommodation) {
            if (empty($accommodation->ref_code)) {
                $accommodation->ref_code = self::generateRefCode();
            }

            if (empty($accommodation->event_id)) {
                $accommodation->event_id = session('current_event_id', getDefaultEventId() ?? null);
            }

            if (auth()->check() && empty($accommodation->created_by)) {
                $accommodation->created_by = auth()->user()->id;
            }
        });

        static::updating(function ($accommodation) {
            if (auth()->check()) {
                $accommodation->updated_by = auth()->user()->id;
            }
        });
    }

    public function getHotelNameAttribute($value)
    {
        $lang = app()->getLocale() ?? 'en';

        if ($lang !== 'en' && !empty($this->attributes['hotel_name_ar'])) {
            return $this->attributes['hotel_name_ar'];
        }

        return $value;
    }


    public function getHotelNameTranslation($lang = false)
    {
        $lang = $lang == false ? getActiveLanguage() : $lang;

        $arabicContent = trim($this->hotel_name_ar);
        $englishContent = trim($this->hotel_name);

        if ($lang === 'ar') {
            return !empty($arabicContent) ? $arabicContent : $englishContent;
        } else if ($lang === 'en') {
            return !empty($englishContent) ? $englishContent : $arabicContent;
        }
        

        return !empty($arabicContent) ? $arabicContent : $englishContent;
    }
}
