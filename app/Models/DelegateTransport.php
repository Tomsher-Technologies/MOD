<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DelegateTransport extends Model
{
    protected $fillable = [
        'delegate_id',
        'type',
        'mode',
        'airport_id',
        'flight_no',
        'flight_name',
        'date_time',
        'status',
        'comment',
    ];

    public function delegate()
    {
        return $this->belongsTo(Delegate::class);
    }

    public function airport()
    {
        return $this->belongsTo(DropdownOption::class, 'airport_id')
            ->whereHas('dropdown', function ($query) {
                $query->where('code', 'airport');
            });
    }
}
