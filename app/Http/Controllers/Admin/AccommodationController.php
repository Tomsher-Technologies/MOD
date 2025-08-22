<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\AccommodationContact;
use App\Models\AccommodationRoom;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{

    public function index()
    {
        $accommodations = Accommodation::with(['rooms', 'contacts'])->latest()->paginate(10);
        return view('admin.accommodations.index', compact('accommodations'));
    }

    public function create()
    {
        return view('admin.accommodations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'rooms.*.room_type' => 'required|string',
            'rooms.*.total_rooms' => 'required|integer',
            'contacts.*.name' => 'required|string',
            'contacts.*.phone' => 'required|string',
        ]);

        $accommodation = Accommodation::create($request->only('hotel_name', 'address', 'contact_number'));

        if ($request->has('rooms')) {
            $accommodation->rooms()->createMany($request->rooms);
        }

        if ($request->has('contacts')) {
            $accommodation->contacts()->createMany($request->contacts);
        }

        return redirect()->route('accommodations.index')->with('success', 'Accommodation added successfully!');
    }

    public function edit(Accommodation $accommodation)
    {
        $accommodation->load('rooms', 'contacts');
        return view('admin.accommodations.edit', compact('accommodation'));
    }

    public function update(Request $request, Accommodation $accommodation)
    {
        $request->validate([
            'hotel_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string',
        ]);

        $accommodation->update($request->only('hotel_name', 'address', 'contact_number'));

        $accommodation->rooms()->delete();
        if ($request->has('rooms')) {
            $accommodation->rooms()->createMany($request->rooms);
        }

        $accommodation->contacts()->delete();
        if ($request->has('contacts')) {
            $accommodation->contacts()->createMany($request->contacts);
        }

        return redirect()->route('accommodations.index')->with('success', 'Accommodation updated successfully!');
    }
}
