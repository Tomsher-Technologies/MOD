<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dropdown;
use App\Models\Delegate;
use App\Models\Escort;
use App\Models\Driver;
use App\Models\Delegation;
use App\Models\Accommodation;
use App\Models\AccommodationContact;
use App\Models\AccommodationRoom;
use Illuminate\Http\Request;
use App\Imports\AccommodationsImport;
use App\Exports\RoomTypesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Traits\HandlesUpdateConfirmation;
use Hash;
use DB;

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
                                        $query->with([ 'gender', 'parent', 'delegateTransports.status','currentRoomAssignment']);
                                    }, 'attachments', 'escorts', 'drivers'
                                ])->findOrFail($id);

        $hotels = Accommodation::where('status', 1)->orderBy('hotel_name', 'asc')->get();
        return view('admin.accommodations.delegations-show', compact('delegation','hotels'));
    }

    public function getHotelRooms($id)
    {
        $rooms = AccommodationRoom::with('roomType')
            ->where('accommodation_id', $id)
            ->get();

        return response()->json($rooms);
    }

     public function assignRoom(Request $request)
    {
        $request->validate([
            'assignable_type' => 'required|in:Delegate,Escort,Driver',
            'assignable_id'   => 'required|integer',
            'hotel_id'        => 'required|integer',
            'room_type_id'    => 'nullable|integer',
            'room_number'     => 'nullable|string',
        ]);

        $model = "App\\Models\\" . $request->assignable_type;
        $user  = $model::findOrFail($request->assignable_id);

        $current = $user->roomAssignments()
                    ->where('hotel_id', $request->hotel_id)
                    ->where('room_type_id', $request->room_type_id)
                    ->where('room_number', $request->room_number)
                    ->where('active_status', 1)
                    ->latest()
                    ->first();

        if (!$current) {
             $alreadyAssigned = \App\Models\RoomAssignment::where('hotel_id', $request->hotel_id)
                                    ->where('room_type_id', $request->room_type_id)
                                    ->where('room_number', $request->room_number)
                                    ->where('assignable_id', '!=', $user->id)
                                    ->where('active_status', 1)
                                    ->exists();

            if ($user->current_room_assignment_id) {
                $oldAssignment = \App\Models\RoomAssignment::find($user->current_room_assignment_id);

                if ($oldAssignment) {
                    $oldRoom = AccommodationRoom::find($oldAssignment->room_type_id);
                    if ($oldRoom && $oldRoom->assigned_rooms > 0) {
                        $oldRoom->assigned_rooms = $oldRoom->assigned_rooms - 1;
                        $oldRoom->save();
                    }
                }
            }

            if (!$alreadyAssigned) {
                $newRoom = AccommodationRoom::find($request->room_type_id);
                if ($newRoom) {
                    $newRoom->assigned_rooms = $newRoom->assigned_rooms + 1;
                    $newRoom->save();
                }
            }

            $user->roomAssignments()->update(['active_status' => 0]);
            
            $assignment = $user->roomAssignments()->create([
                'hotel_id'     => $request->hotel_id,
                'room_type_id' => $request->room_type_id,
                'room_number'  => $request->room_number,
                'assigned_by'  => auth()->user()->id,
                'active_status' => 1
            ]);

            $user->update(['current_room_assignment_id' => $assignment->id]);

            $accommodation = Accommodation::findOrFail($request->hotel_id);
            if ($request->assignable_type === 'Escort') {
                $escort = Escort::findOrFail($request->assignable_id);
                $name = $escort->name_en ?? $escort->name_ar;
            }elseif ($request->assignable_type === 'Driver') {
                $driver = Driver::findOrFail($request->assignable_id);
                $name = $driver->name_en ?? $driver->name_ar;
            }else{
                $delegate = Delegate::findOrFail($request->assignable_id);
                $name = $delegate->name_en ?? $delegate->name_ar;
            }
            
            $hotel = Accommodation::findOrFail($request->hotel_id);

            $changes = [
                        'member_name' => $name,
                        'hotel_name' => $hotel->hotel_name ?? NULL,
                        'room_number' => $request->room_number ?? NULL,
                        ];
            $this->logActivity(
                module: 'Accommodations',
                action: 'assign-room',
                model: $accommodation,
                delegationId: $request->delegation_id,
                changedFields: $changes,
            );

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function hotelOccupancy($hotelId)
    {
        $rooms = RoomAssignment::with(['assignable', 'roomType'])
            ->where('hotel_id', $hotelId)
            ->orderBy('room_type_id')
            ->get();

        return response()->json($rooms);
    }

    

}
