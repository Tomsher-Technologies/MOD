<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dropdown;
use App\Models\Delegation;
use App\Models\Accommodation;
use App\Models\AccommodationContact;
use App\Models\AccommodationRoom;
use Illuminate\Http\Request;
use App\Imports\AccommodationsImport;
use App\Exports\RoomTypesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Traits\HandlesUpdateConfirmation;

class AccommodationController extends Controller
{
    use HandlesUpdateConfirmation;

    public function index(Request $request)
    {
        $accommodations = Accommodation::with(['rooms', 'contacts'])->where('event_id', session('current_event_id', getDefaultEventId() ?? null));
        
        if (request()->has('search')) {
            $accommodations = $accommodations->where('hotel_name', 'like', '%' . request('search') . '%');
        }

        $accommodations = $accommodations->latest()->paginate(15);
        return view('admin.accommodations.index', compact('accommodations'));
    }

    public function create()
    {
        $roomTypes = Dropdown::with('options')->where('code', 'room_type')->first();

        $roomTypes = $roomTypes ? $roomTypes->options : collect();

        return view('admin.accommodations.create', compact('roomTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_name' => 'required',
            'address' => 'nullable',
            'contact_number' => 'required',
            'rooms.*.room_type' => 'required',
            'rooms.*.total_rooms' => 'required',
            'contacts.*.name' => 'required',
            'contacts.*.phone' => 'required',
        ]);

        $accommodation = Accommodation::create([
            'hotel_name' => trim($request->hotel_name) ?? null,
            'address' => $request->address,
            'contact_number' => $request->contact_number
        ]);

        if ($request->has('rooms')) {
            $accommodation->rooms()->createMany($request->rooms);
        }

        if ($request->has('contacts')) {
            $accommodation->contacts()->createMany($request->contacts);
        }

        $this->logActivity(
            module: 'Accommodations',
            action: 'create',
            model: $accommodation,
            submodule: 'managing_members',
            submoduleId: $accommodation->id
        );

        return redirect()->route('accommodations.index')->with('success',  __db('accommodation') . __db('created_successfully'));
    }

    public function edit($id)
    {
        $accommodation = Accommodation::with(['rooms', 'contacts'])->findOrFail(base64_decode($id));

        $roomTypes = Dropdown::with('options')->where('code', 'room_type')->first();

        $roomTypes = $roomTypes ? $roomTypes->options : collect();

        return view('admin.accommodations.edit', compact('accommodation','roomTypes'));
    }

    public function update(Request $request, Accommodation $accommodation)
    {
        $request->validate([
            'hotel_name' => 'required',
            'address' => 'nullable',
            'contact_number' => 'required',
            'rooms.*.room_type' => 'required',
            'rooms.*.total_rooms' => 'required',
            'contacts.*.name' => 'required',
            'contacts.*.phone' => 'required',
        ]);

        $accommodation->update($request->only('hotel_name', 'address', 'contact_number'));

        if ($request->has('rooms')) {
            foreach ($request->rooms as $roomData) {
                if (!empty($roomData['id'])) {
                    $accommodation->rooms()->where('id', $roomData['id'])->update([
                        'room_type'   => $roomData['room_type'],
                        'total_rooms' => $roomData['total_rooms'],
                    ]);
                } else {
                    $accommodation->rooms()->create([
                        'room_type'   => $roomData['room_type'],
                        'total_rooms' => $roomData['total_rooms'],
                    ]);
                }
            }
        }

        $accommodation->contacts()->delete();
        if ($request->has('contacts')) {
            $accommodation->contacts()->createMany($request->contacts);
        }

        return redirect()->route('accommodations.index')->with('success',  __db('accommodation') . __db('updated_successfully'));
    }

    public function destroyRooms($id)
    {
        AccommodationRoom::destroy($id);
    }

    public function showImportForm()
    {
        return view('admin.accommodations.import'); 
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new AccommodationsImport, $request->file('file'));

        return redirect()->route('accommodations.index')
                        ->with('success',  __db('accommodation') . __db('created_successfully'));
    }

    public function exportRoomTypes()
    {
         return Excel::download(new RoomTypesExport, 'room_types.xlsx');
    }

    public function accommodationDelegations(Request $request)
    {
        $query = Delegation::with(['invitationFrom', 'continent','country','invitationStatus','participationStatus','delegates','escorts' ])->orderBy('id', 'desc');

        $currentEventId = session('current_event_id', getDefaultEventId());
        $query->where('event_id', $currentEventId);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhereHas('delegates', function ($delegateQuery) use ($search) {
                        $delegateQuery->where('name_en', 'like', "%{$search}%");
                    })
                    ->orWhereHas('escorts', function ($escortQuery) use ($search) {
                        $escortQuery->where('name_en', 'like', "%{$search}%")
                            ->orWhere('name_ar', 'like', "%{$search}%");
                    });
            });
        }


        if ($invitationFrom = $request->input('invitation_from')) {
            $query->whereIn('invitation_from_id', $invitationFrom);
        }
        if ($continentId = $request->input('continent_id')) {
            $query->where('continent_id', $continentId);
        }
        if ($countryId = $request->input('country_id')) {
            $query->where('country_id', $countryId);
        }
        if ($invitationStatusId = $request->input('invitation_status_id')) {
            $query->where('invitation_status_id', $invitationStatusId);
        }
        if ($participationStatusId = $request->input('participation_status_id')) {
            $query->where('participation_status_id', $participationStatusId);
        }
        if ($hotelName = $request->input('hotel_name')) {
            $query->whereHas('delegates', function ($delegateQuery) use ($hotelName) {
                $delegateQuery->where('hotel_name', $hotelName);
            });
        }

        $delegations = $query->paginate(20);
        return view('admin.accommodations.delegations', compact('delegations'));
    }

    public function accommodationDelegationView (Request $request, $id)
    {
         $delegation = Delegation::with([ 'invitationFrom', 'continent', 'country', 'invitationStatus', 'participationStatus',
                                    'delegates' => function ($query) {
                                        $query->with([ 'gender', 'parent', 'delegateTransports.status']);
                                    }, 'attachments', 'escorts', 'drivers'
                                ])->findOrFail($id);
        return view('admin.accommodations.delegations-show', compact('delegation'));
    }

}
