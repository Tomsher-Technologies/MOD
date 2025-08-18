<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Escort extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_ar',
        'name_en',
        'military_number',
        'delegation_id',
        'phone_number',
        'email',
        'gender_id',
        'nationality_id',
        'date_of_birth',
        'id_number',
        'id_issue_date',
        'id_expiry_date',
        'status',
    ];

    public function delegation()
    {
        return $this->belongsTo(Delegation::class);
    }

    public function gender()
    {
        return $this->belongsTo(DropdownOption::class, 'gender_id');
    }

    public function nationality()
    {
        return $this->belongsTo(DropdownOption::class, 'nationality_id');
    }
}
