<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Delegation extends Model
{
    use HasFactory;

    const ASSIGNABLE_STATUS_CODES = ['2', '10']; 
    const UNASSIGNABLE_STATUS_CODES = ['3', '9']; 

    protected $fillable = [
        'code',
        'updated_by',
        'invitation_from_id',
        'continent_id',
        'country_id',
        'invitation_status_id',
        'participation_status_id',
        'note1',
        'note2',
        'event_id',
    ];

    protected static function booted()
    {
        static::creating(function ($delegation) {
            if (Auth::check()) {
                $delegation->created_by = Auth::id();
            }

            if (!$delegation->event_id) {
                $sessionEventId = Session::get('current_event_id');
                if ($sessionEventId) {
                    $delegation->event_id = $sessionEventId;
                } else {
                    $defaultEventId = getDefaultEventId();
                    $delegation->event_id = $defaultEventId ? $defaultEventId : null;
                }
            }

            $year = date('y');
            $latestDelegation = self::whereYear('created_at', date('Y'))->latest('id')->first();
            $newId = $latestDelegation ? (int)substr($latestDelegation->code, -3) + 1 : 1;
            $delegation->code = 'DA' . $year . '-' . str_pad($newId, 3, '0', STR_PAD_LEFT);
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
        return $this->belongsTo(Country::class);
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

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function attachments()
    {
        return $this->hasMany(DelegationAttachment::class);
    }

    public function escorts()
    {
        return $this->belongsToMany(Escort::class, 'delegation_escorts', 'delegation_id', 'escort_id')
            ->withPivot('status', 'assigned_by')
            ->wherePivot('status', 1)
            ->where('escorts.status', 1);
    }

    public function drivers()
    {
        return $this->belongsToMany(Driver::class, 'delegation_drivers', 'delegation_id', 'driver_id')
            ->withPivot('status', 'assigned_by')
            ->wherePivot('status', 1)
            ->where('drivers.status', 1);
    }

    public function teamHead()
    {
        return $this->delegates()->where('team_head', true)->first();
    }

    public function getTeamHead()
    {
        return $this->delegates()->where('team_head', true)->first();
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function activities()
    {
        return $this->hasMany(DelegationActivity::class, 'module_id')
            ->where('module', 'Delegation')
            ->orderBy('created_at', 'desc');
    }

    public function canAssignServices()
    {
        if (!$this->relationLoaded('invitationStatus')) {
            $this->load('invitationStatus');
        }

        return $this->invitationStatus &&
            in_array($this->invitationStatus->code, self::ASSIGNABLE_STATUS_CODES);
    }

    public function shouldUnassignServices()
    {
        if (!$this->relationLoaded('invitationStatus')) {
            $this->load('invitationStatus');
        }

        return $this->invitationStatus &&
            in_array($this->invitationStatus->code, self::UNASSIGNABLE_STATUS_CODES);
    }
}
