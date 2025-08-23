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
        // Get current event ID from session or default event
        $currentEventId = session('current_event_id', getDefaultEventId());
        
        $query = Escort::with('delegations', 'gender', 'nationality', 'delegation')
            ->where('event_id', $currentEventId)
            ->latest();

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

        if ($request->has('title') && !empty($request->title)) {
            $query->whereIn('internal_ranking_id', $request->title);
        }

        if ($request->has('gender_id') && !empty($request->gender_id)) {
            $query->where('gender_id', $request->gender_id);
        }

        if ($request->has('language_id') && !empty($request->language_id)) {
            $query->where('spoken_languages', 'like', '%' . $request->language_id . '%');
        }

        if ($request->has('delegation_id') && !empty($request->delegation_id)) {
            $query->whereHas('delegations', function ($q) use ($request) {
                $q->where('delegations.id', $request->delegation_id);
            });
        }

        $escorts = $query->paginate(10);
        $delegations = Delegation::where('event_id', $currentEventId)->get();

        return view('admin.escorts.index', compact('escorts', 'delegations'));
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
            'internal_ranking_id' => 'nullable|exists:dropdown_options,id',
            'military_number' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'gender_id' => 'nullable|exists:dropdown_options,id',
            'nationality_id' => 'nullable|exists:dropdown_options,id',
            'date_of_birth' => 'nullable|date',
            'status' => 'nullable|string|max:255',
            'language_id' => 'nullable|array',
        ], [
            'name_en.required' => __db('escort_name_en_required'),
            'name_ar.required' => __db('escort_name_ar_required'),
            'name_en.max' => __db('escort_name_en_max', ['max' => 255]),
            'name_ar.max' => __db('escort_name_ar_max', ['max' => 255]),
            'delegation_id.exists' => __db('delegation_id_exists'),
            'internal_ranking_id.exists' => __db('internal_ranking_id_exists'),
            'military_number.max' => __db('escort_military_number_max', ['max' => 255]),
            'phone_number.max' => __db('escort_phone_number_max', ['max' => 255]),
            'email.max' => __db('escort_email_max', ['max' => 255]),
            'email.email' => __db('escort_email_email'),
            'gender_id.exists' => __db('gender_id_exists'),
            'nationality_id.exists' => __db('nationality_id_exists'),
            'date_of_birth.date' => __db('date_of_birth_date'),
            'status.max' => __db('escort_status_max', ['max' => 255]),
            'language_id.array' => __db('language_id_array'),
        ]);

        $escortData = $request->all();

        if (isset($escortData['language_id'])) {
            $escortData['spoken_languages'] = implode(',', $escortData['language_id']);
            unset($escortData['language_id']);
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
        $escorts = Escort::with('delegations', 'gender', 'nationality')->findOrFail($id);
        return view('admin.escorts.index', compact('escorts'));
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
            'military_number' => 'nullable|string|max:255',
            'name_ar' => 'required|string|max:255',
            'delegation_id' => 'nullable|exists:delegations,id',
            'internal_ranking_id' => 'nullable|exists:dropdown_options,id',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'gender_id' => 'nullable|exists:dropdown_options,id',
            'nationality_id' => 'nullable|exists:dropdown_options,id',
            'date_of_birth' => 'nullable|date',
            'status' => 'nullable|string|max:255',
            'language_id' => 'nullable|array',
        ], [
            'name_en.required' => __db('escort_name_en_required'),
            'name_ar.required' => __db('escort_name_ar_required'),
            'name_en.max' => __db('escort_name_en_max', ['max' => 255]),
            'name_ar.max' => __db('escort_name_ar_max', ['max' => 255]),
            'delegation_id.exists' => __db('delegation_id_exists'),
            'internal_ranking_id.exists' => __db('internal_ranking_id_exists'),
            'military_number.max' => __db('escort_military_number_max', ['max' => 255]),
            'phone_number.max' => __db('escort_phone_number_max', ['max' => 255]),
            'email.max' => __db('escort_email_max', ['max' => 255]),
            'email.email' => __db('escort_email_email'),
            'gender_id.exists' => __db('gender_id_exists'),
            'nationality_id.exists' => __db('nationality_id_exists'),
            'date_of_birth.date' => __db('date_of_birth_date'),
            'status.max' => __db('escort_status_max', ['max' => 255]),
            'language_id.array' => __db('language_id_array'),
        ]);

        $escort = Escort::findOrFail($id);

        // Define relations to compare for confirmation dialog
        $relationsToCompare = [
            'gender_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'nationality_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'nationality_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'internal_ranking_id' => [
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
            'status' => [],
        ];

        // Manually handle spoken_languages comparison
        $originalSpokenLanguages = $escort->spoken_languages ? explode(',', $escort->spoken_languages) : [];
        $newSpokenLanguages = $validated['language_id'] ?? [];

        $customChangedFields = [];

        // Compare and format for display
        $oldLanguageLabels = \App\Models\DropdownOption::whereIn('id', $originalSpokenLanguages)->pluck('value')->implode(', ');
        $newLanguageLabels = \App\Models\DropdownOption::whereIn('id', $newSpokenLanguages)->pluck('value')->implode(', ');

        if ($oldLanguageLabels !== $newLanguageLabels) {
            $customChangedFields['spoken_languages'] = [
                'label' => 'Spoken Languages',
                'old' => $oldLanguageLabels ?: 'N/A',
                'new' => $newLanguageLabels ?: 'N/A',
            ];
        }

        // Remove language_id from validated data before passing to processUpdate
        $validatedDataForProcessUpdate = $validated;
        unset($validatedDataForProcessUpdate['language_id']);

        // Use the processUpdate method for confirmation dialog
        $confirmationResult = $this->processUpdate($request, $escort, $validatedDataForProcessUpdate, $relationsToCompare);

        if ($confirmationResult instanceof \Illuminate\Http\JsonResponse) {
            return $confirmationResult;
        }

        // Merge custom changes with changes from processUpdate
        $confirmationResult['changed_fields'] = array_merge($customChangedFields, $confirmationResult['changed_fields'] ?? []);

        if ($confirmationResult instanceof \Illuminate\Http\JsonResponse) {
            return $confirmationResult;
        }

        $fieldsToNotify = $confirmationResult['notify'] ?? [];

        if (isset($validated['language_id'])) {
            $validated['spoken_languages'] = implode(',', $validated['language_id']);
            unset($validated['language_id']);
        } else {
            $validated['spoken_languages'] = null;
        }

        $escort->update($validated);

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
            'redirect_url' => route('escorts.index'),
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
        // $existingAssignment = $escort->delegations()->where('delegation_id', $delegationId)->first();

        // if ($existingAssignment) {
        //     // If the assignment exists and is marked as unassigned, update the status
        //     if (!$existingAssignment->pivot->status) {
        //         $escort->delegations()->updateExistingPivot($delegationId, [
        //             'status' => 1,
        //             'assigned_by' => auth()->id(),
        //         ]);
        //     }
        // } else {
        // If no assignment exists, create a new one
        $escort->delegations()->attach($delegationId, [
            'status' => 1,
            'assigned_by' => auth()->id(),
        ]);
        // }

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

        return redirect()->back()->with('success', __db('Escort unassigned successfully.'));
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
