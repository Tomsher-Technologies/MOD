<?php

namespace App\Notifications;

use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Notification;

class CustomDatabaseChannel extends DatabaseChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toArray($notifiable);
        
        $alertId = null;
        if (isset($data['alert_id'])) {
            $alertId = $data['alert_id'];
            unset($data['alert_id']);
        }
        
        $eventId = null;
        if (isset($data['event_id'])) {
            $eventId = $data['event_id'];
            unset($data['event_id']); 
        }
        
        return $notifiable->routeNotificationFor('database', $notification)->create([
            'id' => $notification->id,
            'type' => get_class($notification),
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->getKey(),
            'alert_id' => $alertId,
            'event_id' => $eventId,
            'data' => $data,
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}