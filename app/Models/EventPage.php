<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPage extends Model
{
    protected $fillable = ['event_id', 'slug', 'status'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function translations()
    {
        return $this->hasMany(EventPageTranslation::class);
    }

    public function getTranslation($field, $lang = null)
    {
        $lang = $lang ?? app()->getLocale();
        $translation = $this->translations->firstWhere('lang', $lang);

        if (!$translation || empty($translation->$field)) {
            $translation = $this->translations->firstWhere('lang', 'en');
        }

        return $translation ? $translation->$field : null;
    }
}
