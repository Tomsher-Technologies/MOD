<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FloorPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title_en',
        'title_ar',
        'file_paths',
    ];

    protected $casts = [
        'file_paths' => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getFilePathsArrayAttribute()
    {
        return is_array($this->file_paths) ? $this->file_paths : [];
    }

    public function setFilePathsArrayAttribute($value)
    {
        $this->file_paths = is_array($value) ? $value : [];
    }

    public function getTitleAttribute($value)
    {
        $lang = getActiveLanguage();

        if ($lang !== 'en' && !empty($this->attributes['title_ar'])) {
            return $this->attributes['title_ar'];
        }

        if ($lang !== 'ar' && !empty($this->attributes['title_en'])) {
            return $this->attributes['title_en'];
        }

        return $value;
    }
}
