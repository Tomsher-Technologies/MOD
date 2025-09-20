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
        
        if ($lang !== 'en' && !empty($this->attributes['value_ar'])) {
            return $this->attributes['value_ar'];
        }
        
        return $value;
    }
}