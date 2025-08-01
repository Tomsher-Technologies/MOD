<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\EventUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class EventController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:manage_events',  ['only' => ['index','setDefault']]);
        $this->middleware('permission:add_event',  ['only' => ['create','store']]);
        $this->middleware('permission:edit_event',  ['only' => ['edit','update']]);
        $this->middleware('permission:view_event',  ['only' => ['show','index']]);
    }

    public function index(Request $request)
    {
        $request->session()->put('events_last_url', url()->full());
        $events = Event::orderBy('id', 'desc')->paginate(20);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'logo' => 'required|mimes:jpeg,png,jpg,webp,svg,avif|max:1024',
            'image' => 'nullable|mimes:jpeg,png,jpg,webp,svg,avif|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|boolean',
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = uploadImage('events', $request->logo, 'event');
        }

        if ($request->hasFile('image')) {
            $data['image'] = uploadImage('events', $request->image, 'event');
        }

        if (!empty($data['is_default'])) {
            Event::query()->update(['is_default' => false]);
        } else {
            $data['is_default'] = false;
        }
        $data['code'] =  generateEventCode();

        Event::create($data);

        return redirect()->route('events.index')->with('success',  __db('event').__db('created_successfully'));
    }

    public function show($id)
    {
        $id = base64_decode($id);
        $event = Event::findOrFail($id);

        $assignedUsers = EventUserRole::with(['user', 'role'])
                            ->where('event_id', $event->id)
                            ->get()
                            ->groupBy('module'); // group users by module (admin, delegate, etc.)

        $assignedUserIds = $event->assignedUsers->pluck('user_id');
        $availableUsers = User::where('banned', 0)
            ->whereNotIn('id', $assignedUserIds)
            ->get();

        $roles = Role::where('is_active', 1)->get();
        $allModules = ['delegate', 'escort', 'driver', 'hotel']; 
        return view('admin.events.show', compact('event', 'assignedUsers', 'availableUsers','roles','allModules'));
    }

    public function edit($id)
    {
        $id = base64_decode($id);
        $event = Event::find($id);
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'logo' => 'nullable|image|max:1024',
            'image' => 'nullable|image|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|boolean',
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            if ($event->logo) {
                $pathToDelete = str_replace('/storage/', '', $event->logo);
                if (Storage::disk('public')->exists($pathToDelete)) {
                    Storage::disk('public')->delete($pathToDelete);
                }
            }
            $data['logo'] = uploadImage('events', $request->logo, 'event');
        }

        if ($request->hasFile('image')) {
            if ($event->image) {
                $pathToDelete = str_replace('/storage/', '', $event->image);
                if (Storage::disk('public')->exists($pathToDelete)) {
                    Storage::disk('public')->delete($pathToDelete);
                }
            }
            $data['image'] = uploadImage('events', $request->image, 'event');
        }

        if (!empty($data['is_default'])) {
            Event::query()->update(['is_default' => false]);
            $data['is_default'] = true;
        } else {
            $data['is_default'] = false;
        }
       
        $event->update($data);

        return redirect()->route('events.index')->with('success', __db('event').__db('updated_successfully'));
    }

    public function setDefault(Event $event)
    {
        Event::query()->update(['is_default' => false]);
        $event->is_default = true;
        $event->save();

        return redirect()->back()->with('success', 'Default event updated.');
    }

    public function assignUsers(Request $request, Event $event)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'role_id' => 'required|exists:roles,id',
            'module' => 'required|string',
        ]);

        foreach ($request->user_ids as $userId) {
            // Check if user is already assigned to this event and module
            $exists = EventUserRole::where('event_id', $event->id)
                ->where('user_id', $userId)
                ->where('module', $request->module)
                ->exists();

            if (!$exists) {
                EventUserRole::create([
                    'event_id' => $event->id,
                    'user_id' => $userId,
                    'module' => $request->module,
                    'role_id' => $request->role_id,
                ]);
            }
        }

        return 1;
    }

    // Unassign user
    public function unassignUser(Event $event, $assignedId)
    {
        EventUserRole::find($assignedId)->delete();
        return 1;
    }

}