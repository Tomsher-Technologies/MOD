<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HandlesUpdateConfirmation;
use App\Models\Delegation;
use App\Models\Dropdown;
use App\Models\Driver;
use App\Imports\DriverImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DriverController extends Controller
{
    use HandlesUpdateConfirmation;

    const ASSIGNABLE_STATUS_CODES = ['2', '10'];

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:view_drivers|delegate_view_drivers|escort_view_drivers|driver_view_drivers|hotel_view_drivers', [
            'only' => ['index', 'search']
        ]);

        $this->middleware('permission:add_drivers|driver_add_drivers', [
            'only' => ['create', 'store']
        ]);

        $this->middleware('permission:import_drivers', [
            'only' => ['showImportForm', 'import']
        ]);

        $this->middleware('permission:assign_drivers|driver_edit_drivers', [
            'only' => ['assign']
        ]);


        $this->middleware('permission:unassign_drivers|driver_edit_drivers', [
            'only' => ['unassign']
        ]);


        $this->middleware('permission:edit_drivers|driver_edit_drivers', [
            'only' => ['edit', 'update']
        ]);

        $this->middleware('permission:delete_drivers|driver_delete_drivers', [
            'only' => ['destroy']
        ]);
    }

    public function index(Request $request)
    {
        $currentEventId = session('current_event_id', getDefaultEventId());

        $isRedirect = false;
        $referer = $request->headers->get('referer');

        if ($referer) {
            $refererPath = parse_url($referer, PHP_URL_PATH);

            if (
                str_contains($refererPath, '/delegations') ||
                str_contains($refererPath, '/delegations/')
            ) {
                $isRedirect = true;
            }
        }

        $query = Driver::with('delegations')
            ->where('drivers.event_id', $currentEventId)
            ->latest();

        $delegationId = $request->input('delegation_id');
        $assignmentMode = $request->input('assignment_mode');

        if ($delegationId && $assignmentMode === 'driver') {
            $query->whereDoesntHave('delegations', function ($q) use ($delegationId) {
                $q->where('delegations.id', $delegationId)
                    ->where('delegation_drivers.status', 1);
            });

            $query->where('drivers.status', 1);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('drivers.name_en', 'like', "%{$search}%")
                    ->orWhere('drivers.name_ar', 'like', "%{$search}%")
                    ->orWhere('drivers.military_number', 'like', "%{$search}%")
                    ->orWhere('drivers.phone_number', 'like', "%{$search}%")
                    ->orWhere('drivers.driver_id', 'like', "%{$search}%")
                    ->orWhere('drivers.car_type', 'like', "%{$search}%")
                    ->orWhere('drivers.car_number', 'like', "%{$search}%")
                    ->orWhere('drivers.code', 'like', "%{$search}%")
                    ->orWhereHas('delegations', function ($delegationQuery) use ($search) {
                        $delegationQuery->where('code', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('title_en') && !empty($request->title_en)) {
            $titleEns = is_array($request->title_en) ? $request->title_en : [$request->title_en];
            $query->whereIn('title_en', $titleEns);
        }

        if ($request->has('title_ar') && !empty($request->title_ar)) {
            $titleArs = is_array($request->title_ar) ? $request->title_ar : [$request->title_ar];
            $query->whereIn('title_ar', $titleArs);
        }

        if ($request->has('title_id') && !empty($request->title_id)) {
            $titleIds = is_array($request->title_id) ? $request->title_id : [$request->title_id];
            $query->whereIn('title_id', $titleIds);
        }

        if ($request->has('car_type') && !empty($request->car_type)) {
            $carTypes = is_array($request->car_type) ? $request->car_type : [$request->car_type];
            $query->whereIn('car_type', $carTypes);
        }

        if ($request->has('car_number') && !empty($request->car_number)) {
            $carNumbers = is_array($request->car_number) ? $request->car_number : [$request->car_number];
            $query->whereIn('car_number', $carNumbers);
        }

        if ($request->has('capacity') && !empty($request->capacity)) {
            $capacities = is_array($request->capacity) ? $request->capacity : [$request->capacity];
            $query->whereIn('capacity', $capacities);
        }

        if ($request->has('delegation_id') && !empty($request->delegation_id) && $assignmentMode !== 'driver') {
            $delegations = is_array($request->delegation_id) ? $request->delegation_id : [$request->delegation_id];
            $query->whereHas('delegations', function ($q) use ($delegations) {
                $q->whereIn('delegations.id', $delegations);
            });
        }

        $limit = $request->limit ?: 20;

        if ($request->id) {
            $query->where('id', $request->id);
        }

        $drivers = $query->paginate($limit);

        $delegations = Delegation::where('event_id', $currentEventId)
            ->whereHas('invitationStatus', function ($q) {
                $q->whereIn('code', self::ASSIGNABLE_STATUS_CODES);
            })
            ->get();

        $titleEns = Driver::where('event_id', $currentEventId)->whereNotNull('title_en')->distinct()->pluck('title_en')->sort()->values()->all();
        $titleArs = Driver::where('event_id', $currentEventId)->whereNotNull('title_ar')->distinct()->pluck('title_ar')->sort()->values()->all();
        $carTypes = Driver::where('event_id', $currentEventId)->whereNotNull('car_type')->distinct()->pluck('car_type')->sort()->values()->all();
        $carNumbers = Driver::where('event_id', $currentEventId)->whereNotNull('car_number')->distinct()->pluck('car_number')->sort()->values()->all();
        $capacities = Driver::where('event_id', $currentEventId)->whereNotNull('capacity')->distinct()->pluck('capacity')->sort()->values()->all();

        $assignmentDelegation = null;
        if ($delegationId && $assignmentMode === 'driver') {
            $assignmentDelegation = Delegation::find($delegationId);
        }

        $request->session()->put('show_delegations_last_url', url()->full());
        $request->session()->put('edit_drivers_last_url', url()->full());
        $request->session()->put('assign_drivers_last_url', url()->full());

        return view('admin.drivers.index', compact('drivers', 'delegations', 'delegationId', 'assignmentMode', 'assignmentDelegation', 'titleEns', 'titleArs', 'carTypes', 'carNumbers', 'capacities', 'isRedirect', 'request'));
    }


    public function updateStatus(Request $request)
    {
        $driver = Driver::findOrFail($request->id);
        $driver->status = $request->status;
        $driver->save();


        return response()->json(['status' => 'success']);
    }

    public function create()
    {
        $delegations = Delegation::all();

        return view('admin.drivers.create', compact('delegations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'military_number' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string',
            'title_en' => 'nullable|string',
            'name_en' => 'nullable|string|required_without:name_ar',
            'name_ar' => 'nullable|string|required_without:name_en',
            'military_number' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:9|min:9',
            'driver_id' => 'nullable|string|max:255',
            'car_type' => 'nullable|string|max:255',
            'car_number' => 'nullable|string|max:255',
            'unit_id' => 'nullable|exists:dropdown_options,id',
            'capacity' => 'nullable|string|max:255',
            'note1' => 'nullable|string',
            'delegation_id' => 'nullable|exists:delegations,id',
        ], [
            'name_en.required_without' => __db('either_english_name_or_arabic_name'),
            'name_ar.required_without' => __db('either_english_name_or_arabic_name'),
            'military_number.max' => __db('driver_military_number_max', ['max' => 255]),
            'phone_number.max' => __db('driver_phone_number_max', ['max' => 9]),
            'phone_number.min' => __db('driver_phone_number_min', ['min' => 9]),
            'unit_id.exists' => __db('unit_id_exists'),
            'driver_id.max' => __db('driver_id_max', ['max' => 255]),
            'car_type.max' => __db('driver_car_type_max', ['max' => 255]),
            'car_number.max' => __db('driver_car_number_max', ['max' => 255]),
            'capacity.max' => __db('driver_capacity_max', ['max' => 255]),
            'delegation_id.exists' => __db('delegation_id_exists'),
        ]);

        $driverData = $request->all();

        $currentEventId = session('current_event_id', getDefaultEventId());

        $isExistingDriver = Driver::where('event_id', $currentEventId)->where('military_number', $driverData['military_number'])->exists();

        if ($isExistingDriver) {
            return back()->withErrors([
                'military_number' => __db('military_number_exists')
            ])->withInput();
        }

        if (isset($driverData['phone_number']) && !empty($driverData['phone_number'])) {
            $phoneNumber = preg_replace('/[^0-9]/', '', $driverData['phone_number']);
            if (strlen($phoneNumber) === 9) {
                $driverData['phone_number'] = '971' . $phoneNumber;
            }
        }

        $driverData['title'] = $driverData['title_id'] ?? null;
        unset($driverData['title_id']);
        $driver = Driver::create($driverData);

        $this->logActivity(
            module: 'Drivers',
            submodule: 'managing_members',
            action: 'create',
            model: $driver,
            submoduleId: $driver->id,
            delegationId: $driver->delegation_id
        );

        return redirect(getRouteForPage('drivers.index'))->with('success', __db('created_successfully'));
    }

    public function show(string $id)
    {
        $driver = Driver::with('delegations')->findOrFail($id);
        return view('admin.drivers.show', compact('driver'));
    }

    public function edit(string $id)
    {
        $driver = Driver::with('delegations')->findOrFail($id);
        $delegations = Delegation::all();

        return view('admin.drivers.edit', compact('driver', 'delegations'));
    }

    public function assignIndex(Request $request, Driver $driver)
    {
        return view('admin.drivers.assign', compact('driver'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'military_number' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string',
            'title_en' => 'nullable|string',
            'name_en' => 'nullable|string|required_without:name_ar',
            'name_ar' => 'nullable|string|required_without:name_en',
            'phone_number' => 'nullable|string|max:9|min:9',
            'driver_id' => 'nullable|string|max:255',
            'car_type' => 'nullable|string|max:255',
            'car_number' => 'nullable|string|max:255',
            'unit_id' => 'nullable|exists:dropdown_options,id',
            'capacity' => 'nullable|string|max:255',
            'note1' => 'nullable|string',
            'status' => 'nullable|string|max:255',
            'delegation_id' => 'nullable|exists:delegations,id',
        ], [
            'name_en.required_without' => __db('either_english_name_or_arabic_name'),
            'name_ar.required_without' => __db('either_english_name_or_arabic_name'),
            'name_en.max' => __db('driver_name_en_max', ['max' => 255]),
            'name_ar.max' => __db('driver_name_ar_max', ['max' => 255]),
            'military_number.max' => __db('driver_military_number_max', ['max' => 255]),
            'phone_number.max' => __db('driver_phone_number_max', ['max' => 12]),
            'phone_number.min' => __db('driver_phone_number_min', ['min' => 12]),
            'driver_id.max' => __db('driver_id_max', ['max' => 255]),
            'car_type.max' => __db('driver_car_type_max', ['max' => 255]),
            'unit_id.exists' => __db('unit_id_exists'),
            'car_number.max' => __db('driver_car_number_max', ['max' => 255]),
            'capacity.max' => __db('driver_capacity_max', ['max' => 255]),
            'status.max' => __db('driver_status_max', ['max' => 255]),
            'delegation_id.exists' => __db('delegation_id_exists'),
        ]);

        $driverData = $request->all();

        $currentEventId = session('current_event_id', getDefaultEventId());

        $isExistingDriver = Driver::where('event_id', $currentEventId)
            ->where('military_number', $driverData['military_number'])
            ->when(!empty($id), function ($q) use ($id) {
                $q->where('id', '!=', $id);
            })
            ->exists();

        if ($isExistingDriver) {
            return response()->json(['message' => __db('military_number_exists')], 409);
        };

        // if ($isExistingDriver) {
        //     return back()->withErrors([
        //         'military_number' => __db('military_number_exists')
        //     ])->withInput();
        // }

        $driver = Driver::findOrFail($id);

        $relationsToCompare = [
            'title_en' => [],
            'title_ar' => [],
            'delegation_id' => [
                'display_with' => [
                    'model' => \App\Models\Delegation::class,
                    'key' => 'id',
                    'label' => 'code',
                ],
            ],
            'unit_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'military_number' => [],
            'title' => [],
            'name_ar' => [],
            'name_en' => [],
            'phone_number' => [],
            'driver_id' => [],
            'car_type' => [],
            'car_number' => [],
            'capacity' => [],
            'note1' => [],
            'status' => [],
        ];

        if (isset($validated['phone_number']) && !empty($validated['phone_number'])) {
            $phoneNumber = preg_replace('/[^0-9]/', '', $validated['phone_number']);
            if (strlen($phoneNumber) === 9) {
                $validated['phone_number'] = '971' . $phoneNumber;
            }
        }

        $confirmationResult = $this->processUpdate($request, $driver, $validated, $relationsToCompare);

        if ($confirmationResult instanceof \Illuminate\Http\JsonResponse) {
            return $confirmationResult;
        }

        $dataToSave = $confirmationResult['data'];
        $fieldsToNotify = $confirmationResult['notify'] ?? [];



        $dataToSave['title'] = $dataToSave['title_id'] ?? null;
        unset($dataToSave['title_id']);

        $driver->update($dataToSave);

        if ($request->has('changed_fields_json')) {
            $changes = json_decode($request->input('changed_fields_json'), true);
            if (!empty($changes)) {
                $this->logActivity(
                    module: 'Drivers',
                    submodule: 'managing_members',
                    action: 'update',
                    model: $driver,
                    changedFields: $changes,
                    submoduleId: $driver->id,
                    delegationId: $driver->delegation_id,
                    fieldsToNotify: $fieldsToNotify
                );
            }
        }

        if (!empty($fieldsToNotify)) {
            \Illuminate\Support\Facades\Log::info('Admin chose to notify about these driver changes: ' . implode(', ', $fieldsToNotify));
        }

        return response()->json([
            'status' => 'success',
            'message' => __db('updated_successfully'),
            'redirect_url' => getRouteForPage('drivers.edit', $driver->id),
        ]);
    }

    public function destroy(string $id)
    {
        $driver = Driver::findOrFail($id);
        $driver->update(['status' => 0]);

        $this->logActivity(
            module: 'Drivers',
            submodule: 'managing_members',
            action: 'delete',
            model: $driver,
            submoduleId: $driver->id,
            delegationId: $driver->delegation_id
        );

        return redirect(getRouteForPage('drivers.index'))->with('success', __db('deleted_successfully'));
    }

    public function assign(Request $request, Driver $driver)
    {
        $request->validate([
            'delegation_id' => 'required|exists:delegations,id',
            'start_date' => 'nullable|date',
            'action' => 'nullable|in:reassign,replace',
        ]);

        $delegationId = $request->delegation_id;
        $delegation = \App\Models\Delegation::find($delegationId);

        if (!$delegation->canAssignServices()) {
            return back()->withErrors(['error' => __db('services_can_only_be_assigned_to_delegations_with_assignable_status')])->withInput();
        }

        $action = $request->action;
        $startDate = $request->start_date;
        $today = now()->toDateString();

        $activeAssignments = $driver->delegations()->wherePivot('status', 1)->orderBy('pivot_start_date')->get();

        if (!$action) {
            if ($activeAssignments->isEmpty()) {
                $action = 'reassign';
            } else {
                return redirect()->back()->with('error', __db('action_required_for_existing_assignments'));
            }
        }

        if ($activeAssignments->isNotEmpty()) {

            $existingActiveDelegation = $driver->delegations()
                ->where('delegation_id', $delegationId)
                ->wherePivot('status', 1)
                ->exists();

            if ($existingActiveDelegation) {
                return redirect()->route('delegations.show', $delegationId)->with('error', __db('delegation_already_has_same_driver_assignment'));
            }

            if ($action === 'reassign') {
                foreach ($activeAssignments as $assignment) {
                    $assignmentStart = $assignment->pivot->start_date;
                    $assignmentEnd   = $assignment->pivot->end_date;

                    if ($startDate <= $assignmentStart) {
                        $driver->delegations()->updateExistingPivot($assignment->id, [
                            'end_date' => $assignmentStart,
                        ]);
                    } elseif (is_null($assignmentEnd) || $startDate < $assignmentEnd) {
                        $driver->delegations()->updateExistingPivot($assignment->id, [
                            'end_date' => $startDate,
                        ]);
                    }
                }

                $driver->delegations()->attach($delegationId, [
                    'status' => 1,
                    'start_date' => $startDate,
                    'assigned_by' => auth()->id(),
                ]);
            } elseif ($action === 'replace') {
                foreach ($activeAssignments as $assignment) {
                    $driver->delegations()->updateExistingPivot($assignment->id, [
                        'status' => 0,
                    ]);
                }

                $driver->delegations()->attach($delegationId, [
                    'status' => 1,
                    'start_date' => $startDate ?? $today,
                    'assigned_by' => auth()->id(),
                ]);
            }


            if ($driver->current_room_assignment_id) {
                $oldAssignment = \App\Models\RoomAssignment::find($driver->current_room_assignment_id);

                if ($oldAssignment) {
                    $oldRoom = \App\Models\AccommodationRoom::find($oldAssignment->room_type_id);
                    if ($oldRoom && $oldRoom->assigned_rooms > 0) {
                        $alreadyAssignedCount = \App\Models\RoomAssignment::where('hotel_id', $oldAssignment->hotel_id)
                            ->where('room_type_id', $oldAssignment->room_type_id)
                            ->where('room_number', $oldAssignment->room_number)
                            ->where('active_status', 1)
                            ->count();
                        if ($alreadyAssignedCount <= 1 && (strtolower($oldAssignment->room_number) != strtolower($request->room_number) || $oldAssignment->room_type_id != $request->room_type_id || $oldAssignment->hotel_id != $request->hotel_id)) {
                            $oldRoom->assigned_rooms = $oldRoom->assigned_rooms - 1;
                            $oldRoom->save();
                        }
                    }

                    $oldAssignment->active_status = 0;
                    $oldAssignment->save();
                }

                $driver->current_room_assignment_id = null;
                $driver->save();
            }
        } else {
            $driver->delegations()->attach($delegationId, [
                'status' => 1,
                'start_date' => $startDate ?? $today,
                'assigned_by' => auth()->id(),
            ]);
        }

        $this->logActivity(
            module: 'Drivers',
            submodule: 'assignment',
            action: 'assign-drivers',
            model: $driver,
            submoduleId: $driver->id,
            delegationId: $delegationId,
            changedFields: [
                'driver_name' => $driver->name_en,
                'delegation_code' => $delegation->code,
                'action' => $action
            ]
        );

        return redirect()->route('delegations.show', $delegationId)->with('success', __db('updated_successfully'));
    }



    public function unassign(Request $request, Driver $driver)
    {
        $request->validate([
            'delegation_id' => 'required|exists:delegations,id',
        ]);

        $delegationId = $request->delegation_id;
        $delegation = \App\Models\Delegation::find($delegationId);

        $driver->delegations()->updateExistingPivot($delegationId, [
            'status' => 0,
        ]);

        $lastAssignment = $driver->delegations()
            ->wherePivot('status', 1)
            ->orderByPivot('start_date', 'desc')
            ->first();

        if ($lastAssignment) {
            $driver->delegations()->updateExistingPivot($lastAssignment->id, [
                'end_date' => null,
            ]);
        }

        $this->logActivity(
            module: 'Drivers',
            submodule: 'assignment',
            action: 'unassign-drivers',
            model: $driver,
            submoduleId: $driver->id,
            delegationId: $delegationId,
            changedFields: [
                'driver_name' => $driver->name_en,
                'delegation_code' => $delegation->code
            ]
        );

        if ($driver->current_room_assignment_id) {
            $oldAssignment = \App\Models\RoomAssignment::find($driver->current_room_assignment_id);

            if ($oldAssignment) {
                $oldRoom = \App\Models\AccommodationRoom::find($oldAssignment->room_type_id);
                if ($oldRoom && $oldRoom->assigned_rooms > 0) {
                    $alreadyAssignedCount = \App\Models\RoomAssignment::where('hotel_id', $oldAssignment->hotel_id)
                        ->where('room_type_id', $oldAssignment->room_type_id)
                        ->where('room_number', $oldAssignment->room_number)
                        ->where('active_status', 1)
                        ->count();
                    if ($alreadyAssignedCount <= 1 && (strtolower($oldAssignment->room_number) != strtolower($request->room_number) || $oldAssignment->room_type_id != $request->room_type_id || $oldAssignment->hotel_id != $request->hotel_id)) {
                        $oldRoom->assigned_rooms = $oldRoom->assigned_rooms - 1;
                        $oldRoom->save();
                    }
                }

                $oldAssignment->active_status = 0;
                $oldAssignment->save();
            }

            $driver->current_room_assignment_id = null;
            $driver->save();
        }

        return redirect()->back()->with('success', __db('updated_successfully'));
    }

    public function showImportForm()
    {
        return view('admin.drivers.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            $fileName = $request->file('file')->getClientOriginalName();
            Excel::import(new DriverImport($fileName), $request->file('file'));

            return redirect()->route('admin.import-logs.index', ['import_type' => 'drivers'])
                ->with('success', __db('imported_successfully'));
        } catch (\Exception $e) {
            Log::error('Driver Import Error: ' . $e->getMessage());
            return back()->with('error', __db('import_failed') . ': ' . $e->getMessage());
        }
    }
}
