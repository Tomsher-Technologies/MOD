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

    public function getFileObjectsAttribute()
    {
        $filePaths = $this->file_paths;
        
        if (is_array($filePaths) && count($filePaths) > 0) {
            $firstElement = reset($filePaths);
            if (is_string($firstElement)) {
                return array_map(function($path) {
                    return [
                        'path' => $path,
                        'title_en' => basename($path),
                        'title_ar' => ''
                    ];
                }, $filePaths);
            } else {
                return array_map(function($fileObj) {
                    if (isset($fileObj['title']) && !isset($fileObj['title_en'])) {
                        return [
                            'path' => $fileObj['path'],
                            'title_en' => $fileObj['title'],
                            'title_ar' => ''
                        ];
                    }
                    return $fileObj;
                }, $filePaths);
            }
        }
        
        return [];
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
