<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    protected $fillable = [
        'name_en', 'name_ar', 'email', 'phone', 'military_no','event_id',
        'designation_id', 'committee_id'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function designation()
    {
        return $this->belongsTo(DropdownOption::class, 'designation_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'committee_designation');
            });
    }

    public function committee()
    {
        return $this->belongsTo(DropdownOption::class, 'committee_id')
            ->whereHas('dropdown', function ($q) {
                $q->where('code', 'committee');
            });
    }

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? getActiveLanguage() : $lang;

        if ($lang !== 'en') {
            $fieldName = $field.'_ar';
            return !empty($this->$fieldName) ? $this->$fieldName : $this->{$field.'_en'};
        } else {
            return $this->{$field.'_en'};
        }
    }
}
