<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'delegation_id',
        'from_code',
        'to_code',
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
        return $this->belongsTo(Delegate::class, 'from_code');
    }

    public function toDelegate()
    {
        return $this->belongsTo(Delegate::class, 'to_code');
    }

    public function otherMember()
    {
        return $this->belongsTo(OtherInterviewMember::class);
    }
}
