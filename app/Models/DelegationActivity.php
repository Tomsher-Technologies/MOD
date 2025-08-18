<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DelegationActivity extends Model
{
    protected $table = 'delegation_activities';

    protected $fillable = [
        'event_id',
        'action',
        'module',
        'module_id',
        'user_id',
        'changes',
        'message',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Optional relation to user
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function subject() {}
}
