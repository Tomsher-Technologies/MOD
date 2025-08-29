<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DelegationDriver extends Model
{
    protected $fillable = [
        'delegation_id', 'driver_id', 'status', 'assigned_by', 'start_date', 'end_date'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function delegation()
    {
        return $this->belongsTo(Delegation::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
