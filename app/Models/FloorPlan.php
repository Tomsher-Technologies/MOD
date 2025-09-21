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
}
