<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherInterviewMember extends Model
{
    protected $fillable = [
        'name_en',
        'name_ar',
        'status',
        'event_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
