<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventUserRole extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'module', 'role_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
