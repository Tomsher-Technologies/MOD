<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'attachment',
        'send_to_all',
        'created_by'
    ];

    protected $casts = [
        'send_to_all' => 'boolean'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function recipients()
    {
        return $this->belongsToMany(User::class, 'alert_recipients', 'alert_id', 'user_id')
                    ->withPivot('read_at')
                    ->withTimestamps();
    }

    public function alertRecipients()
    {
        return $this->hasMany(AlertRecipient::class);
    }
}