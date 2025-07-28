<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropdownOption extends Model
{
    protected $fillable = ['dropdown_id', 'value', 'sort_order', 'status'];

    public function dropdown()
    {
        return $this->belongsTo(Dropdown::class);
    }
}