<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
        'alert_id'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime'
    ];

    public function getMessageAttribute()
    {
        $data = $this->data;
        $lang = getActiveLanguage();
        
        if (isset($data['message']) && is_array($data['message'])) {
            if ($lang !== 'en' && isset($data['message']['ar'])) {
                return $data['message']['ar'];
            }
            return $data['message']['en'] ?? '';
        }
        
        return $data['message'] ?? '';
    }

    public function getTitleAttribute()
    {
        $data = $this->data;
        $lang = getActiveLanguage();
        
        if (isset($data['title']) && is_array($data['title'])) {
            if ($lang !== 'en' && isset($data['title']['ar'])) {
                return $data['title']['ar'];
            }
            return $data['title']['en'] ?? '';
        }
        
        return $data['title'] ?? '';
    }
}