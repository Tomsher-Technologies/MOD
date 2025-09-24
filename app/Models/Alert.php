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
        'event_id',
        'attachment',
        'send_to_all',
        'created_by'
    ];

    protected $casts = [
        'send_to_all' => 'boolean'
    ];

    public function setTitleAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['title'] = json_encode($value);
        } else {
            $this->attributes['title'] = $value;
        }
    }

    public function setMessageAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['message'] = json_encode($value);
        } else {
            $this->attributes['message'] = $value;
        }
    }

    public function getTitleAttribute($value)
    {
        $title = json_decode($value, true);
        
        if (is_array($title)) {
            $lang = getActiveLanguage();
            if ($lang !== 'en' && isset($title['ar'])) {
                return $title['ar'];
            }
            return $title['en'] ?? '';
        }
        
        return $value;
    }

    public function getMessageAttribute($value)
    {
        $message = json_decode($value, true);
        
        if (is_array($message)) {
            $lang = getActiveLanguage();
            if ($lang !== 'en' && isset($message['ar'])) {
                return $message['ar'];
            }
            return $message['en'] ?? '';
        }
        
        return $value;
    }

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