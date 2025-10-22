<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventPage;
use App\Models\News;
use App\Models\CommitteeMember;
use App\Models\FloorPlan;

class FrontController extends Controller
{
    public function home()
    {
        $lang = app()->getLocale() ?? 'en';
        $eventId = getDefaultEventId() ?? null;
        $page = EventPage::with('translations')->where('event_id', $eventId)->where('status', 1)->where('slug', 'home')->first();

        $news = News::with('event')->where('event_id', $eventId)->where('status', 1)->orderBy('news_date', 'desc')->limit(3)->get();
     
        return view('frontend.index', compact('page','lang','news'));
    }

    public function aboutUs ()
    {
        $lang = app()->getLocale() ?? 'en';
        $eventId = getDefaultEventId() ?? null;
        $page = EventPage::with('translations')->where('event_id', $eventId)->where('status', 1)->where('slug', 'about-us')->first();
        return view('frontend.about_us', compact('page','lang'));
    }

    public function committees(Request $request)
    {
        $lang = app()->getLocale() ?? 'en';
        $eventId = getDefaultEventId() ?? null;
        $page = EventPage::with('translations')->where('event_id', $eventId)->where('status', 1)->where('slug', 'committee')->first();
        
        $query = CommitteeMember::with(['committee', 'designation'])
                                ->where('event_id', $eventId);

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('name_en', 'like', "%{$keyword}%")
                ->orWhere('name_ar', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%")
                ->orWhere('phone', 'like', "%{$keyword}%")
                ->orWhere('military_no', 'like', "%{$keyword}%");
            });
        }
        if ($request->filled('designation_id')) {
            $query->where('designation_id', $request->designation_id);
        }

        if ($request->filled('committee_id')) {
            $query->where('committee_id', $request->committee_id);
        }

        $committees = $query->get();

        $availableDesignations = CommitteeMember::where('event_id', $eventId)
                                                ->with('designation')->get()
                                                ->pluck('designation')
                                                ->unique('id')->filter()->values();

        $availableCommittees = CommitteeMember::where('event_id', $eventId)
                                                ->with('committee')->get()
                                                ->pluck('committee')
                                                ->unique('id')->filter()->values();
            return view('frontend.committees', compact('page','lang','committees','availableDesignations','availableCommittees'));
    }

    public function news()
    {
        $lang = app()->getLocale() ?? 'en';
        $eventId = getDefaultEventId() ?? null;
        $news = News::with('event')->where('event_id', $eventId)->where('status', 1)->orderBy('news_date', 'desc')->paginate(12);
        return view('frontend.news', compact('news','lang'));
    }

    public function newsDetails($id)
    {
        $id = base64_decode($id);
        $lang = app()->getLocale() ?? 'en';
        $eventId = getDefaultEventId() ?? null;
        $news = News::with('event')->where('event_id', $eventId)->where('status', 1)->where('id', $id)->first();

        $relatedNews = News::where('event_id', $eventId)
                            ->where('status', 1)
                            ->where('id', '!=', $id)
                            ->latest('news_date')
                            ->take(4)
                            ->get();
        return view('frontend.news_details', compact('news','lang','relatedNews'));
    }

    public function getFloorPlans()
    {
        $eventId = getDefaultEventId() ?? null;
        
        $floorPlans = FloorPlan::with('event')
            ->where('event_id', $eventId)
            ->get()
            ->map(function ($floorPlan) {
                return [
                    'id' => $floorPlan->id,
                    'title_en' => $floorPlan->title_en,
                    'title_ar' => $floorPlan->title_ar,
                    'file_objects' => $floorPlan->file_objects ?? [],
                ];
            });

        return response()->json($floorPlans);
    }
}
