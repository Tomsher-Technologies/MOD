<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropdownOption extends Model
{
    protected $fillable = ['dropdown_id', 'value', 'value_ar', 'code', 'sort_order', 'status'];

    public function dropdown()
    {
        return $this->belongsTo(Dropdown::class);
    }

    public function getValueAttribute($value)
    {
        $lang = getActiveLanguage();

        if ($lang === 'ar' && !empty($this->attributes['value_ar'])) {
            return $this->attributes['value_ar'];
        }

        if ($lang === 'en' && !empty($this->attributes['value'])) {
            return $this->attributes['value'];
        }

        return !empty($this->attributes['value_ar']) ? $this->attributes['value_ar'] : (!empty($this->attributes['value_en']) ? $this->attributes['value_en'] : $value);
    }

    public function getValueEn()
    {
        return $this->attributes['value'];
    }


    public function getValueAr()
    {
        return $this->attributes['value_ar'];
    }
}
