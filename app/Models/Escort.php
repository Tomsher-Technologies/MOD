<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Escort extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'name_ar',
        'name_en',
        'military_number',
        'delegation_id',
        'gender_id',
        'spoken_languages',
        'internal_ranking_id',
        'status',
        'title',
        'rank',
        'phone_number',
        'email',
        'nationality_id',
        'date_of_birth',
    ];

    protected $casts = [
        'spoken_languages' => 'array',
    ];

    // public function languages()
    // {
    //     return $this->belongsToMany(DropdownOption::class, 'escort_language', 'escort_id', 'language_id');
    // }


    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function delegation()
    {
        return $this->belongsTo(Delegation::class);
    }

    public function gender()
    {
        return $this->belongsTo(DropdownOption::class, 'gender_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'gender');
            });
    }
    public function internalRanking()
    {
        return $this->belongsTo(DropdownOption::class, 'internal_ranking_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'internal_ranking');
            });
    }

    public function nationality()
    {
        return $this->belongsTo(DropdownOption::class, 'nationality_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'nationality');
            });
    }

    public function delegations()
    {
        return $this->belongsToMany(Delegation::class, 'delegation_escorts');
    }
}
