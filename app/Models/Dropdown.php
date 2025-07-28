<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dropdown extends Model
{
    protected $fillable = ['name', 'code', 'status'];

    public function options()
    {
        return $this->hasMany(DropdownOption::class)->orderBy('sort_order');
    }
}
