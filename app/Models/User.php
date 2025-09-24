<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */

    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'user_type',
        'username',
        'force_password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->whereNull('alert_id')->orderBy('created_at', 'desc');
    }

    
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at')->whereNull('alert_id');
    }
    
    
    public function alertNotifications()
    {
        return $this->notifications()->whereNotNull('alert_id');
    }
    
    
    public function unreadAlertNotifications()
    {
        return $this->notifications()->whereNull('read_at')->whereNotNull('alert_id');
    }

    public function eventUserRoles()
    {
        return $this->hasMany(EventUserRole::class, 'user_id');
    }

}
