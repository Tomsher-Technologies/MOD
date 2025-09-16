<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class InterviewMember extends Model
{
    protected $fillable = [
        'created_by',
        'updated_by',
        'type',  // 'from' or 'to'
        'member_id',
        'interview_id',
    ];

    protected static function booted()
    {
        static::creating(function ($interviewMember) {
            if (Auth::check()) {
                $interviewMember->created_by = Auth::id();
            }
        });

        static::updating(function ($interviewMember) {
            if (Auth::check()) {
                $interviewMember->updated_by = Auth::id();
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

    public function interview()
    {
        return $this->belongsTo(Interview::class, 'interview_id');
    }

    public function delegate(){
        return $this->belongsTo(Delegate::class, 'member_id');
    }
    public function fromDelegate()
    {
        return $this->belongsTo(Delegate::class, 'member_id');
    }

    public function toDelegate()
    {
        return $this->belongsTo(Delegate::class, 'member_id');
    }

    public function otherMember()
    {
        return $this->belongsTo(OtherInterviewMember::class, 'member_id');
    }

    public function resolveMemberForInterview(Interview $interview)
    {
        if ($this->type === 'from') {
            return $this->fromDelegate;
        }

        if ($this->type === 'to') {
            if ($interview->type === 'del_others') {
                return $this->otherMember;
            } else {
                return $this->toDelegate;
            }
        }

        return null;
    }
}
