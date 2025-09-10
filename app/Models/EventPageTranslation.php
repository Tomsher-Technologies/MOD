<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPageTranslation extends Model
{
    protected $fillable = ['event_page_id', 'lang', 'title1', 'content1', 'title2', 'content2', 'title3', 'content3', 'title4', 'content4', 'title5', 'content5', 'title6', 'content6', 'title7', 'content7', 'title8', 'content8', 'image', 'link', 'btn_link_1', 'btn_link_2', 'btn_link_3', 'btn_link_4','title9'];

    public function eventPage()
    {
        return $this->belongsTo(EventPage::class);
    }
}
