<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = ['event_id','image', 'news_date', 'status'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function translations()
    {
        return $this->hasMany(NewsTranslation::class);
    }

    public function translate($lang = null)
    {
        $lang = $lang ?? app()->getLocale();
        return $this->translations->where('lang', $lang)->first();
    }

     public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? getActiveLanguage() : $lang;
        $translations = $this->translations->where('lang', $lang)->first();
    
        if (!$translations || empty($translations->$field)) {
            $translations = $this->translations->where('lang', 'en')->first();
        }

        return $translations != null ? $translations->$field : $this->$field;
    }
}
