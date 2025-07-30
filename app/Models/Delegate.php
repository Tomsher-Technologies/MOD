<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delegate extends Model
{
    protected $fillable = [
        'delegation_id',
        'title',
        'name_en',
        'name_ar',
        'gender_id',
        'designation_en',
        'designation_ar',
        'parent_id',
        'relationship',
        'team_head',
        'badge_printed',
    ];

    public function gender()
    {
        return $this->belongsTo(DropdownOption::class, 'gender_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'gender');
            });
    }

    public function delegation()
    {
        return $this->belongsTo(Delegation::class);
    }

    public function transports()
    {
        return $this->hasMany(DelegateTransport::class);
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function parent()
    {
        return $this->belongsTo(Delegate::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Delegate::class, 'parent_id');
    }
}
