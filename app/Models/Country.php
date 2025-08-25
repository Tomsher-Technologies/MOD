<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'short_code',
        'sort_order',
        'status',
        'flag',
    ];
}
