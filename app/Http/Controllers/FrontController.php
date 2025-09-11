<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventPage;

class FrontController extends Controller
{
    public function home()
    {
        $lang = app()->getLocale() ?? 'en';
        $eventId = getDefaultEventId() ?? null;
        $page = EventPage::with('translations')->where('event_id', $eventId)->where('status', 1)->where('slug', 'home')->first();
        return view('frontend.index', compact('page','lang'));
    }

    public function aboutUs ()
    {
        $lang = app()->getLocale() ?? 'en';
        $eventId = getDefaultEventId() ?? null;
        $page = EventPage::with('translations')->where('event_id', $eventId)->where('status', 1)->where('slug', 'about-us')->first();
        return view('frontend.about_us', compact('page','lang'));
    }

    public function committees()
    {
        $lang = app()->getLocale() ?? 'en';
        $eventId = getDefaultEventId() ?? null;
        $page = EventPage::with('translations')->where('event_id', $eventId)->where('status', 1)->where('slug', 'committee')->first();
        return view('frontend.committees', compact('page','lang'));
    }


}
