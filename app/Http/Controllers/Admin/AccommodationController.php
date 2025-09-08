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
use App\Models\RoomAssignment;
use App\Models\ExternalMemberAssignment;
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
        $request->session()->put('accommodations_last_url', url()->full());
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

        return view('admin.accommodations.edit', compact('accommodation', 'roomTypes'));
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

    public function show(Request $request, $hotelId)
    {
        $hotelId = base64_decode($hotelId);

        $roomTypeId   = $request->get('room_type') ?? null;
        $delegationId = $request->get('delegation_id') ?? null;

        $hotel = Accommodation::with('rooms')->findOrFail($hotelId);

        // Delegates
        $delegates = RoomAssignment::with(['assignable', 'roomType.roomType'])
                    ->where('hotel_id', $hotelId)
                    ->where('active_status', 1)
                    ->where('assignable_type', \App\Models\Delegate::class)
                    ->when($roomTypeId, function ($q) use ($roomTypeId) {
                        $q->whereHas('roomType', function ($sub) use ($roomTypeId) {
                            $sub->where('room_type_id', $roomTypeId);
                        });
                    })
                    ->when($delegationId, function ($q) use ($delegationId) {
                        $q->where('delegation_id', $delegationId);
                    })
                    ->get();

        // Escorts
        $escorts = RoomAssignment::with(['assignable', 'roomType.roomType'])
                    ->where('hotel_id', $hotelId)
                    ->where('active_status', 1)
                    ->where('assignable_type', \App\Models\Escort::class)
                    ->when($roomTypeId, function ($q) use ($roomTypeId) {
                        $q->whereHas('roomType', function ($sub) use ($roomTypeId) {
                            $sub->where('room_type_id', $roomTypeId);
                        });
                    })
                    ->when($delegationId, function ($q) use ($delegationId) {
                        $q->where('delegation_id', $delegationId);
                    })
                    ->get();

        // Drivers
        $drivers = RoomAssignment::with(['assignable', 'roomType.roomType'])
                    ->where('hotel_id', $hotelId)
                    ->where('active_status', 1)
                    ->where('assignable_type', \App\Models\Driver::class)
                    ->when($roomTypeId, function ($q) use ($roomTypeId) {
                        $q->whereHas('roomType', function ($sub) use ($roomTypeId) {
                            $sub->where('room_type_id', $roomTypeId);
                        });
                    })
                    ->when($delegationId, function ($q) use ($delegationId) {
                        $q->where('delegation_id', $delegationId);
                    })
                    ->get();

        $externalMembers = ExternalMemberAssignment::where('hotel_id', $hotelId)
                            ->where('active_status', 1)
                            ->when($roomTypeId, function ($q) use ($roomTypeId) {
                                $q->whereHas('roomType', function ($sub) use ($roomTypeId) {
                                    $sub->where('room_type_id', $roomTypeId);
                                });
                            })
                            ->get();

        $rooms = AccommodationRoom::with('roomType','accommodation')
            ->where('accommodation_id', $hotelId)
            ->get();

        $delegations = RoomAssignment::where('hotel_id', $hotelId)
                    ->where('active_status', 1)
                    ->with('delegation') 
                    ->get()
                    ->pluck('delegation')
                    ->unique('id')
                    ->filter() 
                    ->values();

        return view('admin.accommodations.show', compact('hotel', 'delegates', 'escorts', 'drivers','rooms','externalMembers','delegations'));
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
        $query = Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'delegates',
            'escorts',
            'drivers'
        ])->orderBy('id', 'desc');

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
                            ->orWhere('name_ar', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
                    })->orWhereHas('drivers', function ($driversQuery) use ($search) {
                        $driversQuery->where('name_en', 'like', "%{$search}%")
                            ->orWhere('name_ar', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
                    });
            });
        }


        if ($invitationFrom = $request->input('invitation_from')) {
            if (is_array($invitationFrom)) {
                $query->whereIn('invitation_from_id', $invitationFrom);
            } else {
                $query->where('invitation_from_id', $invitationFrom);
            }
        }

        if ($continentId = $request->input('continent_id')) {
            if (is_array($continentId)) {
                $query->whereIn('continent_id', $continentId);
            } else {
                $query->where('continent_id', $continentId);
            }
        }

        if ($countryId = $request->input('country_id')) {
            if (is_array($countryId)) {
                $query->whereIn('country_id', $countryId);
            } else {
                $query->where('country_id', $countryId);
            }
        }

        if ($invitationStatusId = $request->input('invitation_status_id')) {
            if (is_array($invitationStatusId)) {
                $query->whereIn('invitation_status_id', $invitationStatusId);
            } else {
                $query->where('invitation_status_id', $invitationStatusId);
            }
        }

        if ($participationStatusId = $request->input('participation_status_id')) {
            if (is_array($participationStatusId)) {
                $query->whereIn('participation_status_id', $participationStatusId);
            } else {
                $query->where('participation_status_id', $participationStatusId);
            }
        }

        $limit = $request->limit ? $request->limit : 20;

        $delegations = $query->paginate($limit);
        return view('admin.accommodations.delegations', compact('delegations'));
    }

    public function accommodationDelegationView(Request $request, $id)
    {
        $id = base64_decode($id);
        $delegation = Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'delegates' => function ($query) {
                $query->with(['gender', 'parent', 'delegateTransports', 'currentRoomAssignment']);
            },
            'attachments',
            'escorts',
            'drivers'
        ])->findOrFail($id);

        $hotels = Accommodation::where('status', 1)
                    ->whereHas('rooms', function ($q) {
                        $q->where('available_rooms', '>', 0);
                    })
                    ->where('event_id', session('current_event_id', getDefaultEventId() ?? null))
                    ->orderBy('hotel_name', 'asc')
                    ->get();
        return view('admin.accommodations.delegations-show', compact('delegation', 'hotels'));
    }

    public function getHotelRooms($id)
    {
        $rooms = AccommodationRoom::with('roomType','accommodation')
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
            $alreadyAssignedExternal = ExternalMemberAssignment::where('hotel_id', $request->hotel_id)
                ->where('room_type_id', $request->room_type_id)
                ->where('room_number', $request->room_number)
                ->where('active_status', 1)
                ->exists();

            $alreadyAssigned = \App\Models\RoomAssignment::where('hotel_id', $request->hotel_id)
                ->where('room_type_id', $request->room_type_id)
                ->where('room_number', $request->room_number)
                ->where('assignable_id', '!=', $user->id)
                ->where('active_status', 1)
                ->exists();

            $roomType = AccommodationRoom::find($request->room_type_id);
            $availableRooms = $roomType->available_rooms;


            if ($alreadyAssignedExternal) {
                return response()->json(['success' => 3]);
            }

            if ($availableRooms <= 0) {
                return response()->json(['success' => 2]);
            }

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
                'active_status' => 1,
                'delegation_id' => $request->delegation_id
            ]);

            $user->update(['current_room_assignment_id' => $assignment->id]);

            $accommodation = Accommodation::findOrFail($request->hotel_id);
            if ($request->assignable_type === 'Escort') {
                $escort = Escort::findOrFail($request->assignable_id);
                $name = $escort->name_en ?? $escort->name_ar;
            } elseif ($request->assignable_type === 'Driver') {
                $driver = Driver::findOrFail($request->assignable_id);
                $name = $driver->name_en ?? $driver->name_ar;
            } else {
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

            return response()->json(['success' => 1]);
        }

        return response()->json(['success' => 0]);
    }

    public function hotelOccupancy($hotelId)
    {
        $rooms = RoomAssignment::with(['assignable', 'roomType'])
            ->where('hotel_id', $hotelId)
            ->orderBy('room_type_id')
            ->get();

        return response()->json($rooms);
    }

    public function addExternalMembers($id)
    {
        $hotel = Accommodation::findOrFail(base64_decode($id) ?? $id);

        $roomTypes = AccommodationRoom::with('roomType')
            ->where('accommodation_id', $hotel->id)
            ->get();
        return view('admin.accommodations.add-external-members', compact('hotel', 'roomTypes'));
    }

    public function storeExternalMembers(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'room_type' => 'required',
            'room_number' => 'required'
        ], [
            'name.required' => __db('this_field_is_required'),
            'room_type.required' => __db('this_field_is_required'),
            'room_number.required' => __db('this_field_is_required'),
        ]);

        $alreadyAssignedDelegation = \App\Models\RoomAssignment::where('hotel_id', $request->hotel_id)
            ->where('room_type_id', $request->room_type)
            ->where('room_number', $request->room_number)
            ->where('active_status', 1)
            ->exists();

        $alreadyAssigned = ExternalMemberAssignment::where('hotel_id', $request->hotel_id)
            ->where('room_type_id', $request->room_type)
            ->where('room_number', $request->room_number)
            ->where('active_status', 1)
            ->exists();

        $roomType = AccommodationRoom::find($request->room_type);
        $availableRooms = $roomType->available_rooms;

        if ($alreadyAssignedDelegation) {
            return back()->withErrors(['room_error' => __db('room_already_assigned_to_delegation')])->withInput();
        }
        if ($availableRooms <= 0) {
            return back()->withErrors(['room_error' => __db('room_not_available')])->withInput();
        }

        if (!$alreadyAssigned) {
            $newRoom = AccommodationRoom::find($request->room_type);
            if ($newRoom) {
                $newRoom->assigned_rooms = $newRoom->assigned_rooms + 1;
                $newRoom->save();
            }
        }

        $user = ExternalMemberAssignment::create([
            'name' => $request->name,
            'room_type_id' => $request->room_type,
            'room_number' => $request->room_number,
            'hotel_id' => $request->hotel_id,
            'assigned_by'  => auth()->user()->id,
            'active_status' => 1,
        ]);

        $hotel = Accommodation::findOrFail($request->hotel_id);

        $changes = [
            'member_name' => $request->name . ' (' . __db('external_member') . ')',
            'hotel_name' => $hotel->hotel_name ?? NULL,
            'room_number' => $request->room_number ?? NULL,
        ];
        $this->logActivity(
            module: 'Accommodations',
            submodule: 'assignment',
            action: 'assign-room',
            model: $hotel,
            changedFields: $changes,
        );

        return redirect()->back()->with('success', __db('room_assigned'));
    }

    public function getExternalMembers(Request $request)
    {
        $currentEventId = session('current_event_id', getDefaultEventId() ?? null);
        $request->session()->put('external_members_last_url', url()->full());
        
        $externalMembersQuery = ExternalMemberAssignment::with(['hotel', 'roomType.roomType'])
            ->whereHas('hotel', function ($hotelQuery) use ($currentEventId) {
                $hotelQuery->where('event_id', $currentEventId);
            })
            ->where('active_status', 1);
            
        if ($search = $request->input('search')) {
            $externalMembersQuery->where('name', 'like', '%' . $search . '%');
        }
        
        if ($hotelId = $request->input('hotel_id')) {
            $externalMembersQuery->where('hotel_id', $hotelId);
        }
        
        if ($roomTypeId = $request->input('room_type_id')) {
            $externalMembersQuery->where('room_type_id', $roomTypeId);
        }
        
        $externalMembers = $externalMembersQuery->orderBy('id', 'desc')->paginate(10);
        
        $hotels = Accommodation::where('event_id', $currentEventId)
                    ->where('status', 1)
                    ->orderBy('hotel_name', 'asc')
                    ->get();
                    
        $roomTypes = AccommodationRoom::with('roomType')
                        ->whereHas('accommodation', function ($query) use ($currentEventId) {
                            $query->where('event_id', $currentEventId);
                        })
                        ->orderBy('id', 'asc')
                        ->get()
                        ->unique('room_type_id')
                        ->values();
        
        return view('admin.accommodations.view-external-members', compact('externalMembers', 'hotels', 'roomTypes'));
    }

    public function editExternalMembers($id)
    {
        $externalMember = ExternalMemberAssignment::findOrFail($id);
        $hotel = Accommodation::findOrFail($externalMember->hotel_id);
        $roomTypes = AccommodationRoom::with('roomType')
            ->where('accommodation_id', $hotel->id)
            ->get();
        return view('admin.accommodations.edit-external-members', compact('externalMember', 'hotel', 'roomTypes'));
    }

    public function updateExternalMembers(Request $request, $id)
    {

        $request->validate([
            'name' => 'required',
            'room_type' => 'required',
            'room_number' => 'required'
        ], [
            'name.required' => __db('this_field_is_required'),
            'room_type.required' => __db('this_field_is_required'),
            'room_number.required' => __db('this_field_is_required'),
        ]);

        $externalMember = ExternalMemberAssignment::findOrFail($id);

        if ($externalMember->room_type_id != $request->room_type || $externalMember->room_number != $request->room_number || $externalMember->hotel_id != $request->hotel_id) {

            $alreadyAssignedDelegation = \App\Models\RoomAssignment::where('hotel_id', $request->hotel_id)
                ->where('room_type_id', $request->room_type)
                ->where('room_number', $request->room_number)
                ->where('active_status', 1)
                ->exists();

            $alreadyAssigned = ExternalMemberAssignment::where('hotel_id', $request->hotel_id)
                ->where('room_type_id', $request->room_type)
                ->where('room_number', $request->room_number)
                ->where('active_status', 1)
                ->exists();

            $roomType = AccommodationRoom::find($request->room_type);
            $availableRooms = $roomType->available_rooms;

            if ($alreadyAssignedDelegation) {
                return back()->withErrors(['room_error' => __db('room_already_assigned_to_delegation')])->withInput();
            }
            if ($availableRooms <= 0) { // && $externalMember->hotel_id != $request->hotel_id && $externalMember->room_type_id != $request->room_type && $externalMember->room_number != $request->room_number
                return back()->withErrors(['room_error' => __db('room_not_available')])->withInput();
            }

            if (!$alreadyAssigned) {
                $newRoom = AccommodationRoom::find($request->room_type);
                if ($newRoom) {
                    $newRoom->assigned_rooms = $newRoom->assigned_rooms + 1;
                    $newRoom->save();
                }
            }

            if ($externalMember) {
                $oldRoom = AccommodationRoom::find($externalMember->room_type_id);
                if ($oldRoom && $oldRoom->assigned_rooms > 0) {
                    $oldRoom->assigned_rooms = $oldRoom->assigned_rooms - 1;
                    $oldRoom->save();
                }
                $externalMember->active_status = 0;
                $externalMember->save();
            }

            $user = ExternalMemberAssignment::create([
                'name' => $request->name,
                'room_type_id' => $request->room_type,
                'room_number' => $request->room_number,
                'hotel_id' => $request->hotel_id,
                'assigned_by'  => auth()->user()->id,
                'active_status' => 1,
            ]);

            $hotel = Accommodation::findOrFail($request->hotel_id);

            $changes = [
                'member_name' => $request->name . ' (' . __db('external_member') . ')',
                'hotel_name' => $hotel->hotel_name ?? NULL,
                'room_number' => $request->room_number ?? NULL,
            ];
            $this->logActivity(
                module: 'Accommodations',
                submodule: 'assignment',
                action: 'assign-room',
                model: $hotel,
                changedFields: $changes,
            );
        } else {
            $externalMember->update([
                'name' => $request->name,
                'assigned_by'  => auth()->user()->id,
                'active_status' => 1,
            ]);
        }

        return redirect()->back()->with('success', __db('external_member_updated'));
    }

    public function destroyExternalMembers($id)
    {
        $externalMember = ExternalMemberAssignment::findOrFail($id);

        $hotel_id = $externalMember->hotel_id;
        $room_number = $externalMember->room_number;

        if ($externalMember) {
            $oldRoom = AccommodationRoom::find($externalMember->room_type_id);
            if ($oldRoom && $oldRoom->assigned_rooms > 0) {
                $oldRoom->assigned_rooms = $oldRoom->assigned_rooms - 1;
                $oldRoom->save();
            }
            $externalMember->active_status = 0;
            $externalMember->save();
        }

        return redirect()->back()->with('success', __db('external_member_deleted'));
    }
}
