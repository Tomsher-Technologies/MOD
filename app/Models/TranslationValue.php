<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationValue extends Model
{
    protected $fillable = ['translation_id', 'lang', 'value'];

    public function translation()
    {
        return $this->belongsTo(Translation::class);
    }
}

