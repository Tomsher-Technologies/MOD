<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['news_id', 'lang', 'title', 'description'];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}