<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
     protected $fillable = [
        'name_en',
        'name_ar',
        'logo',
        'image',
        'start_date',
        'end_date',
        'status',
        'is_default',
    ];

    // Scope for default event
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
