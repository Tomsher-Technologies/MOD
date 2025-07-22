<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = ['label_key'];

    public function values()
    {
        return $this->hasMany(TranslationValue::class);
    }
}