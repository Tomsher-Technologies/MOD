<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HandlesUpdateConfirmation;
use App\Models\Delegation;
use App\Models\Dropdown;
use App\Models\Escort;
use App\Imports\EscortImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EscortController extends Controller
{
    use HandlesUpdateConfirmation;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:view_escorts|delegate_view_escorts|escort_view_escorts|driver_view_escorts|hotel_view_escorts', [
            'only' => ['index', 'search']
        ]);

        $this->middleware('permission:add_escorts|escort_add_escorts', [
            'only' => ['create', 'store']
        ]);

        $this->middleware('permission:import_escorts|escort_add_escorts', [
            'only' => ['showImportForm', 'import']
        ]);

        $this->middleware('permission:assign_escorts|escort_edit_escorts', [
            'only' => ['assign']
        ]);


        $this->middleware('permission:unassign_escorts|escort_edit_escorts', [
            'only' => ['unassign']
        ]);


        $this->middleware('permission:edit_escorts|escort_edit_escorts', [
            'only' => ['edit', 'update']
        ]);

        $this->middleware('permission:delete_escorts|escort_delete_escorts', [
            'only' => ['destroy']
        ]);
    }


    public function index(Request $request)
    {
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
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('delegations', function ($delegationQuery) use ($search) {
                        $delegationQuery->where('code', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('title_id') && !empty($request->title_id)) {
            $titles = is_array($request->title_id) ? $request->title_id : [$request->title_id];
            $query->whereIn('title_id', $titles);
        }

        if ($request->has('gender_id') && !empty($request->gender_id)) {
            $genders = is_array($request->gender_id) ? $request->gender_id : [$request->gender_id];
            $query->whereIn('gender_id', $genders);
        }

        if ($request->has('language_id') && !empty($request->language_id)) {
            $languages = is_array($request->language_id) ? $request->language_id : [$request->language_id];
            $query->where(function ($q) use ($languages) {
                foreach ($languages as $language) {
                    $q->orWhere('spoken_languages', 'like', '%' . $language . '%');
                }
            });
        }

        if ($request->has('delegation_id') && !empty($request->delegation_id)) {
            $delegations = is_array($request->delegation_id) ? $request->delegation_id : [$request->delegation_id];
            $query->whereHas('delegations', function ($q) use ($delegations) {
                $q->whereIn('delegations.id', $delegations);
            });
        }


        $limit = $request->limit ? $request->limit : 20;

        $escorts = $query->paginate($limit);
        $delegations = Delegation::where('event_id', $currentEventId)->get();

        return view('admin.escorts.index', compact('escorts', 'delegations'));
    }

    public function updateStatus(Request $request)
    {
        $escort = Escort::findOrFail($request->id);
        $escort->status = $request->status;
        $escort->save();

        // if ($request->status == 0) {
        //     $escort->delegations()->updateExistingPivot($escort->delegations->pluck('id'), [
        //         'status' => 0,
        //     ]);
        // }

        return response()->json(['status' => 'success']);
    }

    public function create()
    {
        $delegations = Delegation::all();
        $dropdowns = $this->loadDropdownOptions();

        return view('admin.escorts.create', compact('delegations', 'dropdowns'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'title_en' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|required_without:name_ar',
            'name_ar' => 'nullable|string|required_without:name_en',
            'delegation_id' => 'nullable|exists:delegations,id',
            'internal_ranking_id' => 'nullable|exists:dropdown_options,id',
            'military_number' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:9|min:9',
            'email' => 'nullable|email|max:255',
            'gender_id' => 'nullable|exists:dropdown_options,id',
            'unit_id' => 'nullable|exists:dropdown_options,id',
            'nationality_id' => 'nullable|exists:dropdown_options,id',
            'date_of_birth' => 'nullable|date',
            'status' => 'nullable|string|max:255',
            'language_id' => 'nullable|array',
        ], [
            'name_en.required_without' => __db('either_english_name_or_arabic_name'),
            'name_ar.required_without' => __db('either_english_name_or_arabic_name'),
            'name_en.max' => __db('escort_name_en_max', ['max' => 255]),
            'name_ar.max' => __db('escort_name_ar_max', ['max' => 255]),
            'delegation_id.exists' => __db('delegation_id_exists'),
            'internal_ranking_id.exists' => __db('internal_ranking_id_exists'),
            'military_number.max' => __db('escort_military_number_max', ['max' => 255]),
            'email.max' => __db('escort_email_max', ['max' => 255]),
            'email.email' => __db('escort_email_email'),
            'gender_id.exists' => __db('gender_id_exists'),
            'unit_id.exists' => __db('unit_id_exists'),
            'nationality_id.exists' => __db('nationality_id_exists'),
            'date_of_birth.date' => __db('date_of_birth_date'),
            'status.max' => __db('escort_status_max', ['max' => 255]),
            'language_id.array' => __db('language_id_array'),
        ]);

        $escortData = $request->all();

        // Prepend country code to phone number if it exists
        if (isset($escortData['phone_number']) && !empty($escortData['phone_number'])) {
            $phoneNumber = preg_replace('/[^0-9]/', '', $escortData['phone_number']);
            if (strlen($phoneNumber) === 9) {
                $escortData['phone_number'] = '971' . $phoneNumber;
            }
        }

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

    public function show(string $id)
    {
        $escorts = Escort::with('delegations', 'gender', 'nationality')->findOrFail($id);
        return view('admin.escorts.index', compact('escorts'));
    }

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

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name_en' => 'nullable|string|required_without:name_ar',
            'name_ar' => 'nullable|string|required_without:name_en',
            'title_en' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'military_number' => 'nullable|string|max:255',
            'delegation_id' => 'nullable|exists:delegations,id',
            'internal_ranking_id' => 'nullable|exists:dropdown_options,id',
            'phone_number' => 'nullable|string|max:9|min:9',
            'email' => 'nullable|email|max:255',
            'gender_id' => 'nullable|exists:dropdown_options,id',
            'nationality_id' => 'nullable|exists:dropdown_options,id',
            'unit_id' => 'nullable|exists:dropdown_options,id',
            'date_of_birth' => 'nullable|date',
            'status' => 'nullable|string|max:255',
            'language_id' => 'nullable|array',
        ], [
            'name_en.required_without' => __db('either_english_name_or_arabic_name'),
            'name_ar.required_without' => __db('either_english_name_or_arabic_name'),
            'delegation_id.exists' => __db('delegation_id_exists'),
            'internal_ranking_id.exists' => __db('internal_ranking_id_exists'),
            'military_number.max' => __db('escort_military_number_max', ['max' => 255]),
            'phone_number.max' => __db('escort_phone_number_max', ['max' => 9]),
            'phone_number.min' => __db('escort_phone_number_min', ['min' => 9]),
            'email.max' => __db('escort_email_max', ['max' => 255]),
            'email.email' => __db('escort_email_email'),
            'gender_id.exists' => __db('gender_id_exists'),
            'unit_id.exists' => __db('unit_id_exists'),
            'nationality_id.exists' => __db('nationality_id_exists'),
            'date_of_birth.date' => __db('date_of_birth_date'),
            'status.max' => __db('escort_status_max', ['max' => 255]),
            'language_id.array' => __db('language_id_array'),
        ]);

        $escort = Escort::findOrFail($id);

        $relationsToCompare = [
            'title_en' => [],
            'title_ar' => [],
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
            'internal_ranking_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
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
            'name_ar' => [],
            'name_en' => [],
            'status' => [],
        ];

        if (isset($validated['phone_number']) && !empty($validated['phone_number'])) {
            $phoneNumber = preg_replace('/[^0-9]/', '', $validated['phone_number']);
            if (strlen($phoneNumber) === 9) {
                $validated['phone_number'] = '971' . $phoneNumber;
            }
        }

        $originalSpokenLanguages = $escort->spoken_languages ? explode(',', $escort->spoken_languages) : [];
        $newSpokenLanguages = $validated['language_id'] ?? [];

        $customChangedFields = [];

        $oldLanguageLabels = \App\Models\DropdownOption::whereIn('id', $originalSpokenLanguages)->pluck('value')->implode(', ');
        $newLanguageLabels = \App\Models\DropdownOption::whereIn('id', $newSpokenLanguages)->pluck('value')->implode(', ');


        if ($oldLanguageLabels !== $newLanguageLabels) {
            $customChangedFields['spoken_languages'] = [
                'label' => 'Spoken Languages',
                'old' => $oldLanguageLabels ?: 'N/A',
                'new' => $newLanguageLabels ?: 'N/A',
            ];
        }

        $validatedDataForProcessUpdate = $validated;
        unset($validatedDataForProcessUpdate['language_id']);

        $confirmationResult = $this->processUpdate($request, $escort, $validatedDataForProcessUpdate, $relationsToCompare, $customChangedFields);

        if ($confirmationResult instanceof \Illuminate\Http\JsonResponse) {
            return $confirmationResult;
        }

        $fieldsToNotify = is_array($confirmationResult) ? ($confirmationResult['notify'] ?? []) : [];

        if (isset($validated['language_id'])) {
            $validated['spoken_languages'] = implode(',', $validated['language_id']);
            unset($validated['language_id']);
        } else {
            $validated['spoken_languages'] = null;
        }



        $escort->update($validated);

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
                    delegationId: $escort->delegation_id,
                    fieldsToNotify: $fieldsToNotify
                );
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => __db('Escort updated successfully.'),
            'redirect_url' => route('escorts.edit', $escort->id),
        ]);
    }

    public function destroy(string $id)
    {
        $escort = Escort::findOrFail($id);
        $escort->update(['status' => 0]);

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
        $delegation = \App\Models\Delegation::find($delegationId);

        $escort->delegations()->update([
            'delegation_escorts.status' => 0,
        ]);

        $escort->delegations()->attach([
            $delegationId => [
                'status' => 1,
                'assigned_by' => auth()->id(),
            ],
        ]);

        $this->logActivity(
            module: 'Escorts',
            submodule: 'assignment',
            action: 'assign-escorts',
            model: $escort,
            submoduleId: $escort->id,
            delegationId: $delegationId,
            changedFields: [
                'escort_name' => $escort->name_en,
                'delegation_code' => $delegation->code
            ]
        );

        return redirect()->route('delegations.show', $delegationId)->with('success', __db('Escort assigned successfully.'));
    }



    public function unassign(Request $request, Escort $escort)
    {
        $request->validate([
            'delegation_id' => 'required|exists:delegations,id',
        ]);

        $delegationId = $request->delegation_id;
        $delegation = \App\Models\Delegation::find($delegationId);

        $escort->delegations()->updateExistingPivot($delegationId, [
            'status' => 0,
        ]);

        $this->logActivity(
            module: 'Escorts',
            submodule: 'assignment',
            action: 'unassign-escorts',
            model: $escort,
            submoduleId: $escort->id,
            delegationId: $delegationId,
            changedFields: [
                'escort_name' => $escort->name_en,
                'delegation_code' => $delegation->code
            ]
        );

        if ($escort->current_room_assignment_id) {
            $oldAssignment = \App\Models\RoomAssignment::find($escort->current_room_assignment_id);

            if ($oldAssignment) {
                $oldRoom = \App\Models\AccommodationRoom::find($oldAssignment->room_type_id);
                if ($oldRoom && $oldRoom->assigned_rooms > 0) {
                    $oldRoom->assigned_rooms = $oldRoom->assigned_rooms - 1;
                    $oldRoom->save();
                }

                $oldAssignment->active_status = 0;
                $oldAssignment->save();
            }

            $escort->current_room_assignment_id = null;
            $escort->save();
        }

        return redirect()->back()->with('success', __db('Escort unassigned successfully.'));
    }

    public function showImportForm()
    {
        return view('admin.escorts.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new EscortImport, $request->file('file'));

        return redirect()->route('escorts.index')
            ->with('success',  __db('escort') . __db('created_successfully'));
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
