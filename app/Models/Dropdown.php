<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dropdown extends Model
{
    protected $fillable = ['name', 'name_ar', 'code', 'status'];

    public function options()
    {
        return $this->hasMany(DropdownOption::class)->orderBy('sort_order');
    }

    public function getNameAttribute($value)
    {
        $lang = getActiveLanguage();

        if ($lang !== 'en' && !empty($this->attributes['name_ar'])) {
            return $this->attributes['name_ar'];
        }

        if ($lang !== 'ar' && !empty($this->attributes['value_en'])) {
            return $this->attributes['value_en'];
        }

        return $value;
    }
}
