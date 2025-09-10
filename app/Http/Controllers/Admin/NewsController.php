<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\News;
use App\Models\NewsTranslation;
use App\Models\Event;
use Carbon\Carbon;

class NewsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:manage_news',  ['only' => ['index']]);
        $this->middleware('permission:delete_news',  ['only' => ['destroy']]);
        $this->middleware('permission:add_news',  ['only' => ['create','store']]);
        $this->middleware('permission:edit_news',  ['only' => ['edit','update','updateStatus']]);
    }

    public function index(Request $request)
    {
        $request->session()->put('news_last_url', url()->full());
        $query = News::with('translations')->orderBy('news_date','desc');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            // 1 = active, 2 = inactive; 
            if ($request->status == 1) {
                $query->where('status', 1);
            } elseif ($request->status == 2) {
                $query->where('status', 0);
            }
        }
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($dateRange = $request->input('date_range')) {
            $dates = explode(' - ', $dateRange);

            if (count($dates) === 2) {
                $fromDate = trim($dates[0]);
                $toDate   = trim($dates[1]);

                $query->whereDate('news_date', '>=', $fromDate)
                    ->whereDate('news_date', '<=', $toDate);
            }
        }

        $news = $query->paginate(15);

        $events = Event::orderBy('name_en')->get();
        return view('admin.news.index', compact('news','events'));
    }

    public function create()
    {
        $events = Event::orderBy('name_en')->get();
        return view('admin.news.create', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'news_date' => 'required|date',
            'image' => 'required|image',
            'title_en' => 'required|string|max:255',
            'description_en' => 'required',
            'title_ar' => 'required|string|max:255',
            'description_ar' => 'required',
        ],[
            'event_id.required' => __db('this_field_is_required'),
            'news_date.required' => __db('this_field_is_required'),
            'image.required' => __db('this_field_is_required'),
            'title_en.required' => __db('this_field_is_required'),
            'description_en.required' => __db('this_field_is_required'),
            'title_ar.required' => __db('this_field_is_required'),
            'description_ar.required' => __db('this_field_is_required'),
        ]);

        $data['event_id'] = $request->event_id;
        $data['news_date'] = $request->news_date ? Carbon::parse($request->news_date)->format('Y-m-d') : null;
        $data['status'] = $request->status ?? 1;

        if ($request->hasFile('image')) {
            $data['image'] = uploadImage('news', $request->image, 'news_image');
        }

        $news = News::create($data);

        $translations = [];

        $translations['en'] = [
            'title' => $request->title_en,
            'description' => $request->description_en,
            'lang' => 'en'
        ];

        $translations['ar'] = [
            'title' => $request->title_ar,
            'description' => $request->description_ar,
            'lang' => 'ar'
        ];

        $news->translations()->createMany($translations);

        return redirect()->route('news.index')->with('success',  __db('news') . __db('created_successfully'));
    }

    public function edit(News $news)
    {
        $news->load('translations');
        $events = Event::orderBy('name_en')->get();
        return view('admin.news.edit', compact('news', 'events'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'news_date' => 'required|date',
            'image' => 'nullable|image',
            'title_en' => 'required|string|max:255',
            'description_en' => 'required',
            'title_ar' => 'required|string|max:255',
            'description_ar' => 'required',
        ],[
            'event_id.required' => __db('this_field_is_required'),
            'news_date.required' => __db('this_field_is_required'),
            'image.required' => __db('this_field_is_required'),
            'title_en.required' => __db('this_field_is_required'),
            'description_en.required' => __db('this_field_is_required'),
            'title_ar.required' => __db('this_field_is_required'),
            'description_ar.required' => __db('this_field_is_required'),
        ]);

        $data['event_id'] = $request->event_id;
        
        $data['news_date'] = $request->news_date ? Carbon::parse($request->news_date)->format('Y-m-d') : null;
        $data['status'] = $request->status ?? 1;

        $data['image'] = $news->image;
        if ($request->hasfile('image')) {
            $icon = str_replace('/storage/', '', $news->image);
            if ($icon && Storage::disk('public')->exists($icon)) {
                Storage::disk('public')->delete($icon);
            }
            $data['image'] = uploadImage('news', $request->image, 'news_image');
        }

        $news->update($data);

        $translations = [];

        $translations['en'] = [
            'title' => $request->title_en,
            'description' => $request->description_en,
            'lang' => 'en'
        ];

        $translations['ar'] = [
            'title' => $request->title_ar,
            'description' => $request->description_ar,
            'lang' => 'ar'
        ];

        $news->translations()->where('lang', 'en')->update($translations['en']);
        $news->translations()->where('lang', 'ar')->update($translations['ar']);

        session()->flash('success', __db('news') . __db('updated_successfully'));
        return redirect()->route('news.index');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        if ($news->image != NULL) {
            $icon = str_replace('/storage/', '', $news->image);
            if ($icon && Storage::disk('public')->exists($icon)) {
                Storage::disk('public')->delete($icon);
            }
        }
        $news->delete();
        session()->flash('success', __db('news') . __db('deleted_successfully'));
        return redirect()->route('news.index');
    }

    public function updateStatus(Request $request)
    {
        $news = News::findOrFail($request->id);
        $news->status = $request->status;
        $news->save();
       
        return 1;
    }
}
