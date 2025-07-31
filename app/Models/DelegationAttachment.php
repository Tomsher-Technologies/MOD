<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DelegationAttachment extends Model
{
    protected $fillable = [
        'delegation_id',
        'title_id',
        'file_path',
        'document_date',
    ];

    public function delegation()
    {
        return $this->belongsTo(Delegation::class);
    }

    public function title()
    {
        return $this->belongsTo(DropdownOption::class, 'title_id')
            ->whereHas('dropdown', function ($query) {
                $query->where('code', 'title');
            });
    }
}
