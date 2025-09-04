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
        'note',
        'relationship_id',
        'internal_ranking_id',
        'accommodation_id',
        'accommodation',
        'team_head',
        'badge_printed',
        'current_room_assignment_id'
    ];

    protected $casts = [
        'team_head' => 'boolean',
        'badge_printed' => 'boolean',
        'accommodation' => 'boolean',
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

    public function roomAssignments()
    {
        return $this->morphMany(RoomAssignment::class, 'assignable');
    }


    public function currentRoomAssignment()
    {
        return $this->belongsTo(RoomAssignment::class, 'current_room_assignment_id');
    }

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? getActiveLanguage() : $lang;

        if ($lang !== 'en') {
            $field =  $field . '_ar';
        } else {
            $field =  $field . '_en';
        }

        return $this->$field;
    }
}
