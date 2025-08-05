<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DelegateTransport extends Model
{
    protected $fillable = [
        'created_by',
        'updated_by',
        'delegate_id',
        'type',
        'mode',
        'airport_id',
        'flight_no',
        'flight_name',
        'date_time',
        'status_id',
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

    public function delegate()
    {
        return $this->belongsTo(Delegate::class);
    }

    public function arrivalStatus()
    {
        return $this->belongsTo(DropdownOption::class, 'status_id')
            ->whereHas('dropdown', function ($query) {
                $query->where('code', 'arrival_status');
            });
    }

    public function departureStatus()
    {
        return $this->belongsTo(DropdownOption::class, 'status_id')
            ->whereHas('dropdown', function ($query) {
                $query->where('code', 'departure_status');
            });
    }

    public function airport()
    {
        return $this->belongsTo(DropdownOption::class, 'airport_id')
            ->whereHas('dropdown', function ($query) {
                $query->where('code', 'airport');
            });
    }

    public function getStatus()
    {
        if ($this->type === 'arrival') {
            return $this->arrivalStatus();
        }

        if ($this->type === 'departure') {
            return $this->departureStatus();
        }

        return null;
    }
}
