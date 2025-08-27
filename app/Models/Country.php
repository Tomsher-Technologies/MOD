<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'continent_id',
        'short_code',
        'sort_order',
        'status',
        'flag',
    ];

    public function continent()
    {
        return $this->belongsTo(DropdownOption::class, 'continent_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'continents');
            });
    }
}
