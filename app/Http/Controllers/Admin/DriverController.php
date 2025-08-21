<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HandlesUpdateConfirmation;
use App\Models\Delegation;
use App\Models\Dropdown;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    use HandlesUpdateConfirmation;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:manage_drivers', [
            'only' => ['index', 'search']
        ]);

        $this->middleware('permission:add_drivers', [
            'only' => ['create', 'store']
        ]);

        $this->middleware('permission:edit_drivers', [
            'only' => ['edit', 'update']
        ]);

        $this->middleware('permission:delete_drivers', [
            'only' => ['destroy']
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Driver::with('delegations')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                    ->orWhere('name_ar', 'like', "%{$search}%")
                    ->orWhere('military_number', 'like', "%{$search}%")
                    ->orWhere('mobile_number', 'like', "%{$search}%")
                    ->orWhere('driver_id', 'like', "%{$search}%")
                    ->orWhere('car_type', 'like', "%{$search}%")
                    ->orWhere('car_number', 'like', "%{$search}%")
                    ->orWhereHas('delegations', function ($delegationQuery) use ($search) {
                        $delegationQuery->where('code', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('title') && !empty($request->title)) {
            $query->whereIn('title', $request->title);
        }

        if ($request->has('car_type') && !empty($request->car_type)) {
            $query->whereIn('car_type', $request->car_type);
        }

        if ($request->has('car_number') && !empty($request->car_number)) {
            $query->whereIn('car_number', $request->car_number);
        }

        if ($request->has('capacity') && !empty($request->capacity)) {
            $query->whereIn('capacity', $request->capacity);
        }

        if ($request->has('delegation_id') && !empty($request->delegation_id)) {
            $query->whereHas('delegations', function ($q) use ($request) {
                $q->where('delegations.id', $request->delegation_id);
            });
        }

        $drivers = $query->paginate(10);
        $delegations = Delegation::all();

        return view('admin.drivers.index', compact('drivers', 'delegations'));
    }

    public function updateStatus(Request $request)
    {
        $driver = Driver::findOrFail($request->id);
        $driver->status = $request->status;
        $driver->save();

        if ($request->status == 0) {
            $driver->delegations()->updateExistingPivot($driver->delegations->pluck('id'), [
                'status' => 0,
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $delegations = Delegation::all();
        $dropdowns = $this->loadDropdownOptions();

        return view('admin.drivers.create', compact('delegations', 'dropdowns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'military_number' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'name_ar' => 'required|string|max:255',
            'military_number' => 'nullable|string|max:255',
            'name_en' => 'required|string|max:255',
            'mobile_number' => 'nullable|string|max:255',
            'driver_id' => 'nullable|string|max:255',
            'car_type' => 'nullable|string|max:255',
            'car_number' => 'nullable|string|max:255',
            'capacity' => 'nullable|string|max:255',
            'note1' => 'nullable|string',
            'delegation_id' => 'nullable|exists:delegations,id',
        ]);

        $currentEvent = \App\Models\Event::where('is_default', true)->first();
        $eventId = $currentEvent ? $currentEvent->id : null;

        $driverData = $request->all();
        $driverData['event_id'] = $eventId;

        $driver = Driver::create($driverData);

        $this->logActivity(
            module: 'Drivers',
            submodule: 'managing_members',
            action: 'create',
            model: $driver,
            submoduleId: $driver->id,
            delegationId: $driver->delegation_id
        );

        return redirect(getRouteForPage('drivers.index'))->with('success', __db('Driver created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $driver = Driver::with('delegations')->findOrFail($id);
        return view('admin.drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $driver = Driver::findOrFail($id);
        $delegations = Delegation::all();
        $dropdowns = $this->loadDropdownOptions();

        return view('admin.drivers.edit', compact('driver', 'delegations', 'dropdowns'));
    }

    public function assignIndex(Request $request, Driver $driver)
    {
        return view('admin.drivers.assign', compact('driver'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'military_number' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'mobile_number' => 'nullable|string|max:255',
            'driver_id' => 'nullable|string|max:255',
            'car_type' => 'nullable|string|max:255',
            'car_number' => 'nullable|string|max:255',
            'capacity' => 'nullable|string|max:255',
            'note1' => 'nullable|string',
            'status' => 'nullable|string|max:255',
            'delegation_id' => 'nullable|exists:delegations,id',
        ]);

        $driver = Driver::findOrFail($id);

        // Define relations to compare for confirmation dialog
        $relationsToCompare = [
            'delegation_id' => [
                'display_with' => [
                    'model' => \App\Models\Delegation::class,
                    'key' => 'id',
                    'label' => 'code',
                ],
            ],
            'military_number' => [],
            'title' => [],
            'name_ar' => [],
            'name_en' => [],
            'mobile_number' => [],
            'driver_id' => [],
            'car_type' => [],
            'car_number' => [],
            'capacity' => [],
            'note1' => [],
            'status' => [],
        ];

        // Use the processUpdate method for confirmation dialog
        $confirmationResult = $this->processUpdate($request, $driver, $validated, $relationsToCompare);

        if ($confirmationResult instanceof \Illuminate\Http\JsonResponse) {
            return $confirmationResult;
        }

        $dataToSave = $confirmationResult['data'];
        $fieldsToNotify = $confirmationResult['notify'] ?? [];

        $driver->update($dataToSave);

        // Log activity with changed fields
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
                    delegationId: $driver->delegation_id
                );
            }
        }

        if (!empty($fieldsToNotify)) {
            \Illuminate\Support\Facades\Log::info('Admin chose to notify about these driver changes: ' . implode(', ', $fieldsToNotify));
        }

        return response()->json([
            'status' => 'success',
            'message' => __db('Driver updated successfully.'),
            'redirect_url' => getRouteForPage('drivers.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $driver = Driver::findOrFail($id);
        $driver->update(['status' => 0]);

        // Log activity
        $this->logActivity(
            module: 'Drivers',
            submodule: 'managing_members',
            action: 'delete',
            model: $driver,
            submoduleId: $driver->id,
            delegationId: $driver->delegation_id
        );

        return redirect(getRouteForPage('drivers.index'))->with('success', __db('Driver deleted successfully.'));
    }

    public function assign(Request $request, Driver $driver)
    {
        $request->validate([
            'delegation_id' => 'required|exists:delegations,id',
        ]);

        $delegationId = $request->delegation_id;

        // Check if the driver is already assigned to this delegation
        $existingAssignment = $driver->delegations()->where('delegation_id', $delegationId)->first();

        if ($existingAssignment) {
            // If the assignment exists, ensure its status is 1 (assigned)
            $driver->delegations()->updateExistingPivot($delegationId, [
                'status' => 1,
                'assigned_by' => auth()->id(),
            ]);
        } else {
            // If no assignment exists, create a new one
            $driver->delegations()->attach($delegationId, [
                'status' => 1,
                'assigned_by' => auth()->id(),
            ]);
        }

        return redirect(getRouteForPage('drivers.index'))->with('success', __db('Driver assigned successfully.'));
    }

    public function unassign(Request $request, Driver $driver)
    {
        $request->validate([
            'delegation_id' => 'required|exists:delegations,id',
        ]);

        $delegationId = $request->delegation_id;

        $driver->delegations()->updateExistingPivot($delegationId, [
            'status' => 0,
        ]);

        return redirect(getRouteForPage('drivers.index'))->with('success', __db('Driver unassigned successfully.'));
    }

    protected function loadDropdownOptions()
    {
        $gender = Dropdown::with('options')->where('code', 'gender')->first();
        $nationality = Dropdown::with('options')->where('code', 'nationality')->first();
        $languages = Dropdown::with('options')->where('code', 'language')->first();
        $ranks = Dropdown::with('options')->where('code', 'rank')->first();

        return [
            'genders' => $gender ? $gender->options : collect(),
            'nationalities' => $nationality ? $nationality->options : collect(),
            'languages' => $languages ? $languages->options : collect(),
            'ranks' => $ranks ? $ranks->options : collect(),
        ];
    }
}
