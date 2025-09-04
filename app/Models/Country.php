<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'name_ar',
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

    public function getNameAttribute($value)
    {
        $lang = getActiveLanguage();
        
        if ($lang !== 'en' && !empty($this->attributes['name_ar'])) {
            return $this->attributes['name_ar'];
        }
        
        return $value;
    }
}
