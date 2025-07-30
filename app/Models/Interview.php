<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'delegation_id',
        'from_delegate_id',
        'to_delegate_id',
        'type', // delegate to delegate or delegate to others - del_del or del_others
        'other_member_id',
        'date_time',
        'status',
        'comment',
    ];

    public function delegation()
    {
        return $this->belongsTo(Delegation::class);
    }
    public function fromDelegate()
    {
        return $this->belongsTo(Delegate::class, 'from_delegate_id');
    }

    public function toDelegate()
    {
        return $this->belongsTo(Delegate::class, 'to_delegate_id');
    }

    public function otherMember()
    {
        return $this->belongsTo(OtherInterviewMember::class);
    }
}
