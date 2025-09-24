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

    const ASSIGNABLE_STATUS_CODES = ['2', '10'];

    public function index(Request $request)
    {
        $currentEventId = session('current_event_id', getDefaultEventId() ?? null);
        $request->session()->put('accommodations_last_url', url()->full());
        $accommodations = Accommodation::with(['rooms', 'contacts'])->where('event_id', $currentEventId);

        if (request()->has('search')) {
            $accommodations->where(function ($query) {
                $query->where('hotel_name', 'like', '%' . request()->input('search') . '%')
                    ->orWhere('hotel_name_ar', 'like', '%' . request()->input('search') . '%')
                    ->orWhere('address', 'like', '%' . request()->input('search') . '%')
                    ->orWhere('contact_number', 'like', '%' . request()->input('search') . '%');
            });
        }

        if ($roomTypeId = $request->input('room_type_id')) {
            $accommodations->whereHas('rooms', function ($roomQuery) use ($roomTypeId) {
                $roomQuery->where('room_type', $roomTypeId);
            });
        }
        $limit = $request->input('limit') ?? 10;
        $accommodations = $accommodations->latest()->paginate($limit);

        $roomTypes = getDropDown('room_type');
        $roomTypes = $roomTypes ? $roomTypes->options : collect();

        return view('admin.accommodations.index', compact('accommodations', 'roomTypes'));
    }

    public function create()
    {
        $roomTypes = getDropDown('room_type');

        $roomTypes = $roomTypes ? $roomTypes->options : collect();

        return view('admin.accommodations.create', compact('roomTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_name'     => 'required_without:hotel_name_ar',
            'hotel_name_ar'  => 'required_without:hotel_name',
            'address' => 'nullable',
            'contact_number' => 'nullable',
            'rooms.*.room_type' => 'nullable',
            'rooms.*.total_rooms' => 'nullable',
            'contacts.*.name' => 'nullable',
            'contacts.*.phone' => 'nullable',
        ], [
            'hotel_name.required_without' => __db('fill_either_english_or_arabic_field'),
            'hotel_name_ar.required_without' => __db('fill_either_english_or_arabic_field'),
        ]);

        $accommodation = Accommodation::create([
            'hotel_name' => trim($request->hotel_name) ?? null,
            'hotel_name_ar' => trim($request->hotel_name_ar) ?? null,
            'address' => $request->address,
            'contact_number' => $request->contact_number
        ]);

        if ($request->has('rooms')) {
            $rooms = collect($request->rooms)
                ->filter(function ($room) {
                    return !empty($room['room_type']) || !empty($room['total_rooms']);
                })
                ->toArray();

            if (!empty($rooms)) {
                $accommodation->rooms()->createMany($rooms);
            }
        }

        if ($request->has('contacts')) {
            $contacts = collect($request->contacts)
                ->filter(function ($contact) {
                    return !empty($contact['name']) || !empty($contact['phone']);
                })
                ->toArray();

            if (!empty($contacts)) {
                $accommodation->contacts()->createMany($contacts);
            }
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

        $roomTypes = getDropDown('room_type');

        $roomTypes = $roomTypes ? $roomTypes->options : collect();

        return view('admin.accommodations.edit', compact('accommodation', 'roomTypes'));
    }

    public function update(Request $request, Accommodation $accommodation)
    {
        $request->validate([
            'hotel_name'     => 'required_without:hotel_name_ar',
            'hotel_name_ar'  => 'required_without:hotel_name',
            'address' => 'nullable',
            'contact_number' => 'nullable',
            'rooms.*.room_type' => 'nullable',
            'rooms.*.total_rooms' => 'nullable',
            'contacts.*.name' => 'nullable',
            'contacts.*.phone' => 'nullable',
        ], [
            'hotel_name.required_without' => __db('fill_either_english_or_arabic_field'),
            'hotel_name_ar.required_without' => __db('fill_either_english_or_arabic_field'),
        ]);

        $accommodation->update($request->only('hotel_name', 'hotel_name_ar', 'address', 'contact_number'));

        if ($request->has('rooms')) {
            foreach ($request->rooms as $roomData) {
                if (!empty($roomData['id'])) {
                    $accommodation->rooms()->where('id', $roomData['id'])->update([
                        'room_type'   => $roomData['room_type'],
                        'total_rooms' => $roomData['total_rooms'],
                    ]);
                } else {
                    if (!empty($roomData['room_type']) && !empty($roomData['total_rooms'])) {
                        $accommodation->rooms()->create([
                            'room_type'   => $roomData['room_type'],
                            'total_rooms' => $roomData['total_rooms'],
                        ]);
                    }
                }
            }
        }

        $accommodation->contacts()->delete();
        if ($request->has('contacts')) {
            $contacts = collect($request->contacts)
                ->filter(function ($contact) {
                    return !empty($contact['name']) || !empty($contact['phone']);
                })
                ->toArray();

            if (!empty($contacts)) {
                $accommodation->contacts()->createMany($contacts);
            }
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
            ->select('room_assignments.*')
            ->where('hotel_id', $hotelId)
            ->where('active_status', 1)
            ->where('assignable_type', \App\Models\Delegate::class)
            ->when($roomTypeId, function ($q) use ($roomTypeId) {
                $q->whereHas('roomType', function ($sub) use ($roomTypeId) {
                    $sub->where('room_type_id', $roomTypeId);
                });
            })
            ->when($delegationId, function ($q) use ($delegationId) {
                $q->where('room_assignments.delegation_id', $delegationId);
                   
            })
            ->leftJoin('delegations', 'room_assignments.delegation_id', '=', 'delegations.id')

            ->leftJoin('countries as country_sort', 'delegations.country_id', '=', 'country_sort.id')
            ->leftJoin('dropdown_options as invitation_from_sort', 'delegations.invitation_from_id', '=', 'invitation_from_sort.id')

            ->orderBy('country_sort.sort_order', 'asc')
            ->orderBy('invitation_from_sort.sort_order', 'asc')
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

        $rooms = AccommodationRoom::with('roomType', 'accommodation')
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

        return view('admin.accommodations.show', compact('hotel', 'delegates', 'escorts', 'drivers', 'rooms', 'externalMembers', 'delegations'));
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
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            $fileName = $request->file('file')->getClientOriginalName();
            Excel::import(new AccommodationsImport($fileName), $request->file('file'));
            
            return redirect()->route('admin.import-logs.index', ['import_type' => 'hotels'])
                ->with('success', __db('accommodations_imported_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', __db('accommodations_import_failed') . ': ' . $e->getMessage());
        }
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
        ])
            ->whereHas('invitationStatus', function ($q) {
                $q->whereIn('code', self::ASSIGNABLE_STATUS_CODES);
            });

        $query->leftJoin('countries as country_sort', 'delegations.country_id', '=', 'country_sort.id')
            ->leftJoin('dropdown_options as invitation_from_sort', 'delegations.invitation_from_id', '=', 'invitation_from_sort.id')
            ->leftJoin('dropdown_options as participation_status_sort', 'delegations.participation_status_id', '=', 'participation_status_sort.id')
            ->orderBy('country_sort.sort_order', 'asc')
            ->orderBy('invitation_from_sort.sort_order', 'asc')
            ->orderBy('participation_status_sort.sort_order', 'asc')
            ->select('delegations.*');


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
        $rooms = AccommodationRoom::with('roomType', 'accommodation')
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
            'delegation_id'   => 'required|integer',
        ]);

        $delegation = Delegation::find($request->delegation_id);
        if (!$delegation || !$delegation->canAssignServices()) {
            return response()->json(['success' => 4, 'message' => 'Hotel assignments can only be made for delegations with status Accepted or Accepted with Secretary.']);
        }

        $model = "App\Models\\" . $request->assignable_type;
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
                        $alreadyAssignedCount = RoomAssignment::where('hotel_id', $oldAssignment->hotel_id)
                            ->where('room_type_id', $oldAssignment->room_type_id)
                            ->where('room_number', $oldAssignment->room_number)
                            ->where('active_status', 1)
                            ->count();
                        if ($alreadyAssignedCount <= 1 && (strtolower($oldAssignment->room_number) != strtolower($request->room_number) || $oldAssignment->room_type_id != $request->room_type_id || $oldAssignment->hotel_id != $request->hotel_id)) {
                            $oldRoom->assigned_rooms = $oldRoom->assigned_rooms - 1;
                            $oldRoom->save();
                        }
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

            return response()->json(['success' => 1, 'assignment_id' => $assignment->id]);
        }

        return response()->json(['success' => 0,  'assignment_id' => null]);
    }

    public function hotelOccupancy($hotelId)
    {
        $rooms = RoomAssignment::with(['assignable', 'roomType'])
            ->where('hotel_id', $hotelId)
            ->orderBy('room_type_id')
            ->get();

        return response()->json($rooms);
    }

    public function addExternalMembers(Request $request, $id)
    {
        $request->session()->put('external_members_last_url', url()->full());
        $hotel = Accommodation::findOrFail(base64_decode($id) ?? $id);

        $roomTypes = AccommodationRoom::with('roomType')
            ->where('accommodation_id', $hotel->id)
            ->where('available_rooms', '>', 0)
            ->get();

        $externalMembersQuery = ExternalMemberAssignment::with(['hotel', 'roomType.roomType'])
            ->where('hotel_id', $hotel->id)
            ->where('active_status', 1);

        if ($search = $request->input('search')) {
            $externalMembersQuery->where('name', 'like', '%' . $search . '%')->orWhere('coming_from', 'like', '%' . $search . '%');
        }

        if ($roomTypeId = $request->input('room_type_id')) {
            $externalMembersQuery->where('room_type_id', $roomTypeId);
        }

        $externalMembers = $externalMembersQuery->orderBy('id', 'desc')->get();
        return view('admin.accommodations.add-external-members', compact('hotel', 'roomTypes', 'externalMembers'));
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
            'coming_from' => $request->coming_from,
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
            $externalMembersQuery->where('name', 'like', '%' . $search . '%')->orWhere('coming_from', 'like', '%' . $search . '%');
        }

        if ($request->has('room_number')) {
            $externalMembersQuery->where('room_number', 'like', '%' . $request->input('room_number') . '%');
        }
        if ($hotelId = $request->input('hotel_id')) {
            $externalMembersQuery->where('hotel_id', $hotelId);
        }

        if ($roomTypeId = $request->input('room_type_id')) {
            $externalMembersQuery->where('room_type_id', $roomTypeId);
        }
        $limit = $request->input('limit') ?? 10;
        $externalMembers = $externalMembersQuery->orderBy('id', 'desc')->paginate($limit);

        $hotels = Accommodation::where('event_id', $currentEventId)
            ->where('status', 1)
            ->orderBy('hotel_name', 'asc')
            ->get();

        $roomTypes = [];
        if ($request->input('hotel_id')) {
            $roomTypes = AccommodationRoom::with('roomType', 'accommodation')
                ->where('accommodation_id', $request->input('hotel_id'))
                ->get();
        }

        return view('admin.accommodations.view-external-members', compact('externalMembers', 'hotels', 'roomTypes'));
    }

    public function editExternalMembers($id)
    {
        $externalMember = ExternalMemberAssignment::findOrFail($id);
        $hotel = Accommodation::findOrFail($externalMember->hotel_id);
        $roomTypes = AccommodationRoom::with('roomType')
            ->where('accommodation_id', $hotel->id)
            ->where('available_rooms', '>', 0)
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

            if ($externalMember && ((($externalMember->room_type_id != $request->room_type) && (strtolower($externalMember->room_number) != strtolower($request->room_number))) || (($externalMember->room_type_id == $request->room_type) && (strtolower($externalMember->room_number) != strtolower($request->room_number))) || (($externalMember->room_type_id != $request->room_type) && (strtolower($externalMember->room_number) == strtolower($request->room_number))) )  ) {
                $oldRoom = AccommodationRoom::find($externalMember->room_type_id);
                if ($oldRoom && $oldRoom->assigned_rooms > 0) {
                    $alreadyAssignedCount = ExternalMemberAssignment::where('hotel_id', $externalMember->hotel_id)
                        ->where('room_type_id', $externalMember->room_type_id)
                        ->where('room_number', $externalMember->room_number)
                        ->where('active_status', 1)
                        ->count();
                    if ($alreadyAssignedCount <= 1 && ((strtolower($externalMember->hotel_id) != strtolower($request->hotel_id)) || (strtolower($externalMember->room_type_id) != strtolower($request->room_type)) || (strtolower($externalMember->room_number) != strtolower($request->room_number)))) {
                        $oldRoom->assigned_rooms = $oldRoom->assigned_rooms - 1;
                        $oldRoom->save();
                    }
                }
            }
            $externalMember->active_status = 0;
            $externalMember->save();

            $user = ExternalMemberAssignment::create([
                'name' => $request->name,
                'coming_from' => $request->coming_from,
                'room_type_id' => $request->room_type,
                'room_number' => $request->room_number,
                'hotel_id' => $request->hotel_id,
                'assigned_by'  => auth()->user()->id,
                'active_status' => 1,
            ]);

            $externalMemberId = $user->id;
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
                'coming_from' => $request->coming_from,
                'assigned_by'  => auth()->user()->id,
                'active_status' => 1,
            ]);
            $externalMemberId = $externalMember->id;
        }

        return redirect()->route('external-members.edit', $externalMemberId)->with('success', __db('external_member_updated'));
    }

    public function destroyExternalMembers($id)
    {
        $externalMember = ExternalMemberAssignment::findOrFail($id);

        $hotel_id = $externalMember->hotel_id;
        $room_number = $externalMember->room_number;

        if ($externalMember) {
            $oldRoom = AccommodationRoom::find($externalMember->room_type_id);

            $alreadyAssignedCount = ExternalMemberAssignment::where('hotel_id', $externalMember->hotel_id)
                ->where('room_type_id', $externalMember->room_type_id)
                ->where('room_number', $externalMember->room_number)
                ->where('active_status', 1)
                ->count();

            if ($oldRoom && $oldRoom->assigned_rooms > 0) {
                if ($alreadyAssignedCount <= 1) {
                    $oldRoom->assigned_rooms = $oldRoom->assigned_rooms - 1;
                    $oldRoom->save();
                }
            }
            $externalMember->active_status = 0;
            $externalMember->save();
        }

        return redirect()->back()->with('success', __db('external_member_deleted'));
    }

    public function unassignAccommodation(Request $request)
    {
        $assignment = RoomAssignment::find($request->assignable_id);

        $hotel_id = $assignment?->hotel_id ?? NULL;
        $room_number = $assignment?->room_number ?? NULL;

        if ($assignment) {
            $type = $assignment->assignable_type;
            $oldRoom = AccommodationRoom::find($assignment->room_type_id);

            $alreadyAssignedCount = RoomAssignment::where('hotel_id', $assignment->hotel_id)
                ->where('room_type_id', $assignment->room_type_id)
                ->where('room_number', $assignment->room_number)
                ->where('active_status', 1)
                ->count();

            if ($oldRoom && $oldRoom->assigned_rooms > 0) {
                if ($alreadyAssignedCount <= 1) {
                    $oldRoom->assigned_rooms = $oldRoom->assigned_rooms - 1;
                    $oldRoom->save();
                }
            }
            $assignment->active_status = 0;
            $assignment->save();

            if ($type == 'App\Models\Driver') {
                $driver = Driver::find($assignment->assignable_id);
                $driver->current_room_assignment_id = NULL;
                $driver->save();
            } elseif ($type == 'App\Models\Escort') {
                $escort = Escort::find($assignment->assignable_id);
                $escort->current_room_assignment_id = NULL;
                $escort->save();
            } elseif ($type == 'App\Models\Delegate') {
                $delegate = Delegate::find($assignment->assignable_id);
                $delegate->current_room_assignment_id = NULL;
                $delegate->save();
            }

            return response()->json(['success' => 1]);
        }

        return response()->json(['success' => 0]);
    }
}
