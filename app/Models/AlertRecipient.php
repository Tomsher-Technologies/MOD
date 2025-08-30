<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'alert_id',
        'user_id',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime'
    ];

    public function alert()
    {
        return $this->belongsTo(Alert::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}