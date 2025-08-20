<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Interview extends Model
{
    protected $fillable = [
        'delegation_id',
        'created_by',
        'updated_by',
        'type', // delegate to delegate or delegate to others - del_del or del_others
        'interview_with', //delegation id
        'other_member_id',
        'date_time',
        'status_id',
        'comment',
    ];

    protected static function booted()
    {
        static::creating(function ($interview) {
            if (Auth::check()) {
                $interview->created_by = Auth::id();
            }
        });

        static::updating(function ($interview) {
            if (Auth::check()) {
                $interview->updated_by = Auth::id();
            }
        });
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function delegation()
    {
        return $this->belongsTo(Delegation::class, 'delegation_id');
    }

    public function fromMembers()
    {
        return $this->interviewMembers()->where('type', 'from');
    }

    public function toMembers()
    {
        return $this->interviewMembers()->where('type', 'to');
    }


    public function interviewMembers()
    {
        return $this->hasMany(InterviewMember::class);
    }

    public function interviewWithDelegation()
    {
        return $this->belongsTo(Delegation::class, 'interview_with');
    }

    public function otherMember()
    {
        return $this->belongsTo(OtherInterviewMember::class, 'other_member_id');
    }


    public function status()
    {
        return $this->belongsTo(DropdownOption::class, 'status_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'interview_status');
            });
    }
}
