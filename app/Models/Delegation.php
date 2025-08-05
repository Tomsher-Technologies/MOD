<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Delegation extends Model
{
    protected $fillable = [
        'updated_by',
        'code',
        'invitation_from_id',
        'continent_id',
        'country_id',
        'invitation_status_id',
        'participation_status_id',
        'note1',
        'note2',
    ];

    protected static function booted()
    {
        static::creating(function ($delegation) {
            if (Auth::check()) {
                $delegation->created_by = Auth::id();
            }
        });

        static::updating(function ($delegation) {
            if (Auth::check()) {
                $delegation->updated_by = Auth::id();
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

    public function invitationFrom()
    {
        return $this->belongsTo(DropdownOption::class, 'invitation_from_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'departments');
            });
    }


    public function continent()
    {
        return $this->belongsTo(DropdownOption::class, 'continent_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'continents');
            });
    }

    public function country()
    {
        return $this->belongsTo(DropdownOption::class, 'country_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'country');
            });
    }

    public function invitationStatus()
    {
        return $this->belongsTo(DropdownOption::class, 'invitation_status_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'invitation_status');
            });
    }

    public function participationStatus()
    {
        return $this->belongsTo(DropdownOption::class, 'participation_status_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'participation_status');
            });
    }

    public function delegates()
    {
        return $this->hasMany(Delegate::class);
    }

    public function attachments()
    {
        return $this->hasMany(DelegationAttachment::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
