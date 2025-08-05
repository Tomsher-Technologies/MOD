<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewMember extends Model
{
    protected $fillable = [
        'type',  // 'from' or 'to'
        'member_id',
        'interview_id',
    ];
    public function interview()
    {
        return $this->belongsTo(Interview::class);
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
