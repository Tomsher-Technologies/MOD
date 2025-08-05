<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delegate extends Model
{
    protected $fillable = [
        'delegation_id',
        'code',
        'title_id',
        'name_en',
        'name_ar',
        'gender_id',
        'designation_en',
        'designation_ar',
        'parent_id',
        'relationship_id',
        'internal_ranking_id',
        'driver_id',
        'escort_id',
        'accommodation_id',
        'team_head',
        'badge_printed',
    ];

    public function title()
    {
        return $this->belongsTo(DropdownOption::class, 'title_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'title');
            });
    }
    public function gender()
    {
        return $this->belongsTo(DropdownOption::class, 'gender_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'gender');
            });
    }

    public function relationship()
    {
        return $this->belongsTo(DropdownOption::class, 'relationship_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'relationship');
            });
    }

    public function internalRanking()
    {
        return $this->belongsTo(DropdownOption::class, 'internal_ranking_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'internal_ranking');
            });
    }

    public function delegation()
    {
        return $this->belongsTo(Delegation::class);
    }

    public function delegateTransports()
    {
        return $this->hasMany(DelegateTransport::class);
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
