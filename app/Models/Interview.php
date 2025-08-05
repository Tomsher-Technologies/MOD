<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'delegation_id',
        'type', // delegate to delegate or delegate to others - del_del or del_others
        'interview_with', //delegation id
        'other_member_id',
        'date_time',
        'status',
        'comment',
    ];

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
