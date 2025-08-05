<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Interview extends Model
{
    protected $fillable = [
        'created_by',
        'updated_by',
        'delegation_id',
        'type', // delegate to delegate or delegate to others - del_del or del_others
        'interview_with', //delegation id
        'other_member_id',
        'date_time',
        'status',
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
        return $this->belongsTo(Delegation::class);
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
        return $this->belongsTo(OtherInterviewMember::class);
    }
}
