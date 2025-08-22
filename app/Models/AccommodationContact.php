<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccommodationContact extends Model
{
    protected $fillable = ['accommodation_id', 'name', 'phone'];

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }
}
