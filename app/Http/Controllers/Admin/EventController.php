<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index()
    {
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

        Event::create($data);

        return redirect()->route('events.index')->with('success',  __db('event').__db('created_successfully'));
    }

    public function edit(Event $event)
    {
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

        // Handle logo upload and delete old logo if exists
        if ($request->hasFile('logo')) {
            if ($event->logo) {
                Storage::disk('public')->delete($event->logo);
            }
            $data['logo'] = uploadImage('events', $request->logo, 'event');
        }

        // Handle image upload and delete old image if exists
        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = uploadImage('events', $request->image, 'event');
        }

        // If is_default is set, unset others
        if (!empty($data['is_default'])) {
            Event::query()->update(['is_default' => false]);
            $data['is_default'] = true;
        } else {
            $data['is_default'] = false;
        }

        $event->update($data);

        return redirect()->route('events.index')->with('success', __db('event').__db('updated_successfully'));
    }

    public function destroy(Event $event)
    {
        // Delete images
        if ($event->logo) {
            Storage::disk('public')->delete($event->logo);
        }
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
    }

    // Extra method to set default event from index (optional)
    public function setDefault(Event $event)
    {
        Event::query()->update(['is_default' => false]);
        $event->is_default = true;
        $event->save();

        return redirect()->back()->with('success', 'Default event updated.');
    }
}