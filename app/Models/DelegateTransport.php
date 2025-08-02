<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DelegateTransport extends Model
{
    protected $fillable = [
        'code',
        'type',
        'mode',
        'airport_id',
        'flight_no',
        'flight_name',
        'date_time',
        'status_id',
        'comment',
    ];

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
