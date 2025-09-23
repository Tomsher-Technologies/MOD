<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $fillable = [
        'import_type',
        'file_name',
        'row_number',
        'error_message',
        'row_data',
        'status'
    ];

    protected $casts = [
        'row_data' => 'array'
    ];
}
