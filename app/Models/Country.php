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
        'code',
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

    public function getNameEn()
    {
        return $this->attributes['name'];
    }


    public function getNameAr()
    {
        return $this->attributes['name_ar'];
    }
}
