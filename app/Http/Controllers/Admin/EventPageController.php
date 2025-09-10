<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventPage;
use App\Models\EventPageTranslation;
use Illuminate\Http\Request;

class EventPageController extends Controller
{
     function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:manage_pages',  ['only' => ['index']]);
        $this->middleware('permission:view_news',  ['only' => ['index']]);
        $this->middleware('permission:edit_pages',  ['only' => ['edit','update']]);
    }

    public function index(Request $request)
    {
        $eventId =  session('current_event_id', getDefaultEventId() ?? null);

        $event = Event::find($eventId);

        $defaultSlugs = ['home','about-us','committee'];

        $pages = [];
        if ($eventId) {
            foreach ($defaultSlugs as $slug) {
                $page = EventPage::firstOrCreate(
                    ['event_id' => $eventId, 'slug' => $slug],
                    ['status' => 1]
                );

                foreach(['en','ar'] as $lang){
                    if(!$page->translations()->where('lang',$lang)->exists()){
                        $page->translations()->create([
                            'lang' => $lang,
                            'title1' => '',
                            'content1' => '',
                        ]);
                    }
                }
                $pages[] = $page;
            }
        }

        return view('admin.pages.index', compact('event', 'pages', 'eventId'));
    }

    public function edit($eventPageId)
    {
        $eventPageId = base64_decode($eventPageId);
        $page = EventPage::with('translations')->findOrFail($eventPageId);

        if($page->slug == 'home') {
            return view('admin.pages.home', compact('page'));
        }else if($page->slug == 'about-us') {
            return view('admin.pages.about_us', compact('page'));
        }else if($page->slug == 'committee') {
            return view('admin.pages.committee', compact('page'));
        }
       return redirect()->route('event_pages.index');
    }

    // Update page content
    public function update(Request $request, $eventPageId)
    {
        $eventPageId = base64_decode($eventPageId);
        $page = EventPage::with('translations')->findOrFail($eventPageId);

        $fields = [
            'title1','content1','title2','content2','title3','content3',
            'title4','content4','title5','content5','title6','content6',
            'title7','content7','title8','content8','title9',
            'image','link','btn_link_1','btn_link_2','btn_link_3','btn_link_4'
        ];

        $translationsInput = $request->input('translations', []);

        foreach ($page->translations as $translation) {
            $lang = $translation->lang;

            if (!isset($translationsInput[$lang])) continue;

            $updateData = [];

            foreach ($fields as $f) {
                if (isset($translationsInput[$lang][$f])) {
                    $updateData[$f] = $translationsInput[$lang][$f];
                }
            }

            foreach ($fields as $f) {
                if ($request->hasFile("translations.$lang.$f")) {
                    if (!empty($translation->$f) && file_exists(public_path($translation->$f))) {
                        unlink(public_path($translation->$f));
                    }

                    $file = $request->file("translations.$lang.$f");
                    $path = $file->store('event_pages', 'public');
                    $updateData[$f] = '/storage/' . $path;
                }
            }

            $translation->update($updateData);
        }

        return redirect()->route('event_pages.index')->with('success', __db('page').__db('updated_successfully'));
    }
}
