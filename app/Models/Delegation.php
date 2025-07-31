<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    protected $fillable = [
        'delegate_id',
        'invitation_from_id',
        'continent_id',
        'country_id',
        'invitation_status_id',
        'participation_status_id',
        'note1',
        'note2',
    ];

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
