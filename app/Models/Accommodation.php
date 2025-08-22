<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    protected $fillable = ['hotel_name', 'address', 'contact_number','status'];

    public function rooms()
    {
        return $this->hasMany(AccommodationRoom::class);
    }

    public function contacts()
    {
        return $this->hasMany(AccommodationContact::class);
    }
}
