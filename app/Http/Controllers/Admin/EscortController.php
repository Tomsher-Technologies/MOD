<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HandlesUpdateConfirmation;
use App\Models\Delegation;
use App\Models\Dropdown;
use App\Models\Escort;
use Illuminate\Http\Request;

class EscortController extends Controller
{
    use HandlesUpdateConfirmation;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:manage_escorts', [
            'only' => ['index', 'search']
        ]);

        $this->middleware('permission:add_escorts', [
            'only' => ['create', 'store']
        ]);

        $this->middleware('permission:edit_escorts', [
            'only' => ['edit', 'update']
        ]);

        $this->middleware('permission:delete_escorts', [
            'only' => ['destroy']
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Escort::with('delegations', 'gender', 'nationality')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                    ->orWhere('name_ar', 'like', "%{$search}%")
                    ->orWhere('military_number', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('delegations', function ($delegationQuery) use ($search) {
                        $delegationQuery->where('code', 'like', "%{$search}%");
                    });
            });
        }

        $escorts = $query->paginate(10);

        // return response()->json(['sss' => $escorts]);

        return view('admin.escorts.index', compact('escorts'));
    }

    public function updateStatus(Request $request)
    {
        $escort = Escort::findOrFail($request->id);
        $escort->status = $request->status;
        $escort->save();

        if ($request->status == 0) {
            $escort->delegations()->updateExistingPivot($escort->delegations->pluck('id'), [
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

        return view('admin.escorts.create', compact('delegations', 'dropdowns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'delegation_id' => 'nullable|exists:delegations,id',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'gender_id' => 'nullable|exists:dropdown_options,id',
            'nationality_id' => 'nullable|exists:dropdown_options,id',
            'date_of_birth' => 'nullable|date',
            'id_number' => 'nullable|string|max:255',
            'id_issue_date' => 'nullable|date',
            'id_expiry_date' => 'nullable|date',
            'status' => 'nullable|string|max:255',
            'language_id' => 'nullable|array',
        ]);

        $currentEvent = \App\Models\Event::where('is_default', true)->first();
        $eventId = $currentEvent ? $currentEvent->id : null;

        $escortData = $request->all();
        $escortData['event_id'] = $eventId;

        if ($request->has('language_id')) {
            $escortData['spoken_languages'] = implode(',', $request->input('language_id'));
        } else {
            $escortData['spoken_languages'] = null;
        }

        $escort = Escort::create($escortData);

        $this->logActivity(
            module: 'Escorts',
            submodule: 'managing_members',
            action: 'create',
            model: $escort,
            submoduleId: $escort->id,
            delegationId: $escort->delegation_id
        );

        return redirect()->route('escorts.index')->with('success', __db('Escort created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $escort = Escort::with('delegations', 'gender', 'nationality')->findOrFail($id);
        return view('admin.escorts.show', compact('escort'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $escort = Escort::findOrFail($id);
        $delegations = Delegation::all();
        $dropdowns = $this->loadDropdownOptions();

        return view('admin.escorts.edit', compact('escort', 'delegations', 'dropdowns'));
    }

    public function assignIndex(Request $request, Escort $escort)
    {
        return view('admin.escorts.assign', compact('escort'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'delegation_id' => 'nullable|exists:delegations,id',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'gender_id' => 'nullable|exists:dropdown_options,id',
            'nationality_id' => 'nullable|exists:dropdown_options,id',
            'date_of_birth' => 'nullable|date',
            'id_number' => 'nullable|string|max:255',
            'id_issue_date' => 'nullable|date',
            'id_expiry_date' => 'nullable|date',
            'status' => 'nullable|string|max:255',
            'language_id' => 'nullable|array',
        ]);

        $escort = Escort::findOrFail($id);

        // Define relations to compare for confirmation dialog
        $relationsToCompare = [
            'status' => [], // Simple string comparison for 'status' field
        ];

        // Use the processUpdate method for confirmation dialog
        $confirmationResult = $this->processUpdate($request, $escort, $validated, $relationsToCompare);

        if ($confirmationResult instanceof \Illuminate\Http\JsonResponse) {
            return $confirmationResult;
        }

        $dataToSave = $confirmationResult['data'];
        $fieldsToNotify = $confirmationResult['notify'] ?? [];

        if (isset($dataToSave['language_id'])) {
            $dataToSave['spoken_languages'] = implode(',', $dataToSave['language_id']);
            unset($dataToSave['language_id']); // Remove original language_id as it's not a column
        } else {
            $dataToSave['spoken_languages'] = null;
        }

        $escort->update($dataToSave);

        // Log activity with changed fields
        if ($request->has('changed_fields_json')) {
            $changes = json_decode($request->input('changed_fields_json'), true);
            if (!empty($changes)) {
                $this->logActivity(
                    module: 'Escorts',
                    submodule: 'managing_members',
                    action: 'update',
                    model: $escort,
                    changedFields: $changes,
                    submoduleId: $escort->id,
                    delegationId: $escort->delegation_id
                );
            }
        }

        if (!empty($fieldsToNotify)) {
            \Illuminate\Support\Facades\Log::info('Admin chose to notify about these escort changes: ' . implode(', ', $fieldsToNotify));
        }

        return response()->json([
            'status' => 'success',
            'message' => __db('Escort updated successfully.'),
            'redirect_url' => route('escorts.index'), // Or escorts.show if there is one
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $escort = Escort::findOrFail($id);
        $escort->update(['status' => 0]);

        // Log activity
        $this->logActivity(
            module: 'Escorts',
            submodule: 'managing_members',
            action: 'delete',
            model: $escort,
            submoduleId: $escort->id,
            delegationId: $escort->delegation_id
        );

        return redirect()->route('escorts.index')->with('success', __db('Escort deleted successfully.'));
    }

    public function assign(Request $request, Escort $escort)
    {
        $request->validate([
            'delegation_id' => 'required|exists:delegations,id',
        ]);

        $delegationId = $request->delegation_id;

        // Check if the escort is already assigned to this delegation
        $existingAssignment = $escort->delegations()->where('delegation_id', $delegationId)->first();

        if ($existingAssignment) {
            // If the assignment exists and is marked as unassigned, update the status
            if (!$existingAssignment->pivot->status) {
                $escort->delegations()->updateExistingPivot($delegationId, [
                    'status' => 1,
                    'assigned_by' => auth()->id(),
                ]);
            }
        } else {
            // If no assignment exists, create a new one
            $escort->delegations()->attach($delegationId, [
                'status' => 1,
                'assigned_by' => auth()->id(),
            ]);
        }

        return redirect()->route('escorts.index')->with('success', __db('Escort assigned successfully.'));
    }

    public function unassign(Request $request, Escort $escort)
    {
        $request->validate([
            'delegation_id' => 'required|exists:delegations,id',
        ]);

        $delegationId = $request->delegation_id;

        $escort->delegations()->updateExistingPivot($delegationId, [
            'status' => 0,
        ]);

        return redirect()->route('escorts.index')->with('success', __db('Escort unassigned successfully.'));
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
