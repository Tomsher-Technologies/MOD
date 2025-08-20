<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DelegationEscort extends Model
{
    protected $fillable = [
        'delegation_id',
        'escort_id',
        'status',
        'assigned_by',
    ];
}
