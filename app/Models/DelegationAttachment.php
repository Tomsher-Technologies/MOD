<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DelegationAttachment extends Model
{
    protected $fillable = [
        'delegation_id',
        'title',
        'file_path',
        'document_date',
    ];

    public function delegation()
    {
        return $this->belongsTo(Delegation::class);
    }
}
