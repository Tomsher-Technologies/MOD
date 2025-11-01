<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HandlesUpdateConfirmation;
use App\Models\Delegation;
use App\Models\Dropdown;
use App\Models\Escort;
use App\Imports\EscortImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EscortController extends Controller
{
    use HandlesUpdateConfirmation;

    const ASSIGNABLE_STATUS_CODES = ['2', '10'];

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:view_escorts|delegate_view_escorts|escort_view_escorts|driver_view_escorts|hotel_view_escorts', [
            'only' => ['index', 'search']
        ]);

        $this->middleware('permission:add_escorts|escort_add_escorts', [
            'only' => ['create', 'store']
        ]);

        $this->middleware('permission:import_escorts', [
            'only' => ['showImportForm', 'import']
        ]);

        $this->middleware('permission:assign_escorts|escort_edit_escorts', [
            'only' => ['assign']
        ]);


        $this->middleware('permission:unassign_escorts|escort_edit_escorts', [
            'only' => ['unassign']
        ]);


        $this->middleware('permission:import_escorts', [
            'only' => ['exportExcel']
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

        $currentEventId = session('current_event_id', getDefaultEventId());

        $query = Escort::with('delegations', 'gender', 'nationality', 'delegation')
            ->select('escorts.*')
            ->where('escorts.event_id', $currentEventId);

        $delegationId = $request->input('delegation_id');
        $assignmentMode = $request->input('assignment_mode');

        if ($request->has('id')) {
            $query->where('escorts.id', $request->id);
        }

        if ($delegationId && $assignmentMode === 'escort') {
            // $query->where('escorts.delegation_id', null)->where('escorts.status', 1);
            $query->where('escorts.status', 1);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('escorts.name_en', 'like', "%{$search}%")
                    ->orWhere('escorts.name_ar', 'like', "%{$search}%")
                    ->orWhere('escorts.military_number', 'like', "%{$search}%")
                    ->orWhere('escorts.phone_number', 'like', "%{$search}%")
                    ->orWhere('escorts.code', 'like', "%{$search}%")
                    ->orWhere('escorts.email', 'like', "%{$search}%")
                    ->orWhereHas('delegations', function ($delegationQuery) use ($search) {
                        $delegationQuery->where('code', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('title_en') && !empty($request->title_en)) {
            $titleEns = is_array($request->title_en) ? $request->title_en : [$request->title_en];
            $query->whereIn('escorts.title_en', $titleEns);
        }

        if ($request->has('title_ar') && !empty($request->title_ar)) {
            $titleArs = is_array($request->title_ar) ? $request->title_ar : [$request->title_ar];
            $query->whereIn('escorts.title_ar', $titleArs);
        }

        if ($request->has('gender_id') && !empty($request->gender_id)) {
            $genders = is_array($request->gender_id) ? $request->gender_id : [$request->gender_id];
            $query->whereIn('escorts.gender_id', $genders);
        }

        if ($request->has('language_id') && !empty($request->language_id)) {
            $languages = is_array($request->language_id) ? $request->language_id : [$request->language_id];
            $query->where(function ($q) use ($languages) {
                foreach ($languages as $language) {
                    $q->orWhere('escorts.spoken_languages', 'like', '%' . $language . '%');
                }
            });
        }

        if ($request->has('delegation_id') && !empty($request->delegation_id) && $assignmentMode !== 'escort') {
            $delegations = is_array($request->delegation_id) ? $request->delegation_id : [$request->delegation_id];
            $query->whereHas('delegations', function ($q) use ($delegations) {
                $q->whereIn('delegations.id', $delegations);
            });
        }

        if ($request->has('internal_ranking_id') && !empty($request->internal_ranking_id)) {
            $rankIds = is_array($request->internal_ranking_id) ? $request->internal_ranking_id : [$request->internal_ranking_id];
            $query->whereIn('escorts.internal_ranking_id', $rankIds);
        }

        if ($request->has('assigned') && !empty($request->assigned)) {
            if ($request->assigned == 'assigned') {
                $query->whereHas('delegations', function ($q) {
                    $q->where('delegation_escorts.status', 1);
                });
            } elseif ($request->assigned == 'unassigned') {
                $query->whereDoesntHave('delegations', function ($q) {
                    $q->where('delegation_escorts.status', 1);
                });
            }
        }

        $query->leftJoin('dropdown_options as rankings', 'escorts.internal_ranking_id', '=', 'rankings.id')
            ->leftJoin('delegation_escorts as de', function ($join) {
                $join->on('escorts.id', '=', 'de.escort_id')
                    ->where('de.status', '=', 1);
            })
            ->select('escorts.*')
            ->orderByRaw('CASE WHEN de.escort_id IS NULL THEN 0 ELSE 1 END')
            ->orderByRaw('CAST(escorts.military_number AS UNSIGNED) ASC')
            ->orderBy('rankings.sort_order');

        $limit = $request->limit ? $request->limit : 20;

        $escorts = $query->paginate($limit);

        $delegations = Delegation::where('event_id', $currentEventId)
            ->whereHas('invitationStatus', function ($q) {
                $q->whereIn('code', self::ASSIGNABLE_STATUS_CODES);
            })
            ->get();

        $titleEns = Escort::where('event_id', $currentEventId)->whereNotNull('title_en')->distinct()->pluck('title_en')->sort()->values()->all();
        $titleArs = Escort::where('event_id', $currentEventId)->whereNotNull('title_ar')->distinct()->pluck('title_ar')->sort()->values()->all();
        $rankings = \App\Models\DropdownOption::whereHas('dropdown', function ($q) {
            $q->where('code', 'rank');
        })->orderBy('sort_order')->get();

        $assignmentDelegation = null;

        if ($delegationId && $assignmentMode === 'escort') {
            $assignmentDelegation = Delegation::find($delegationId);
        }

        if (!isset($assignmentMode)) {
            $request->session()->put('show_delegations_last_url', url()->full());
        }

        $request->session()->put('edit_escorts_last_url', url()->full());
        $request->session()->put('assign_escorts_last_url', url()->full());
        $request->session()->put('import_escorts_last_url', url()->full());

        return view('admin.escorts.index', compact('escorts', 'delegations', 'delegationId', 'assignmentMode', 'assignmentDelegation', 'titleEns', 'titleArs', 'rankings', 'isRedirect', 'request'));
    }

    public function updateStatus(Request $request)
    {
        $escort = Escort::findOrFail($request->id);
        $escort->status = $request->status;
        $escort->save();


        return response()->json(['status' => 'success']);
    }

    public function create()
    {
        $delegations = Delegation::all();

        return view('admin.escorts.create', compact('delegations',));
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
            'military_number.military_number_exists' => __db('military_number_exists'),
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

        $currentEventId = session('current_event_id', getDefaultEventId());

        $isExistingEscort = Escort::where('event_id', $currentEventId)->where('military_number', $escortData['military_number'])->exists();

        if ($isExistingEscort && !empty($escortData['military_number'])) {
            return back()->withErrors([
                'military_number' => __db('military_number_exists')
            ])->withInput();
        }

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

        return redirect()->route('escorts.index')->with('success', __db('created_successfully'));
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

        return view('admin.escorts.edit', compact('escort', 'delegations',));
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


        $escortData = $request->all();

        $currentEventId = session('current_event_id', getDefaultEventId());

        $isExistingEscort = Escort::where('event_id', $currentEventId)
            ->where('military_number', $escortData['military_number'])
            ->when(!empty($id), function ($q) use ($id) {
                $q->where('id', '!=', $id);
            })
            ->exists();

        if ($isExistingEscort && !empty($escortData['military_number'])) {
            return response()->json(['message' => __db('military_number_exists')], 409);
        };

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
            'message' => __db('updated_successfully'),
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

        return redirect()->route('escorts.index')->with('success', __db('deleted_successfully'));
    }

    public function assign(Request $request, Escort $escort)
    {
        $request->validate([
            'delegation_id' => 'required|exists:delegations,id',
        ]);

        $delegationId = $request->delegation_id;
        $delegation = \App\Models\Delegation::find($delegationId);

        if (!$delegation->canAssignServices()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => __db('services_can_only_be_assigned_to_delegations_with_assignable_status')
                ], 422);
            }
            return back()->withErrors(['error' => __db('services_can_only_be_assigned_to_delegations_with_assignable_status')])->withInput();
        }

        if ($escort->delegations()->wherePivot('status', 1)->exists()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => __db('escort_already_assigned')
                ], 422);
            }
            return back()->withErrors(['error' => __db('escort_already_assigned')])->withInput();
        }

        try {
            $escort->delegations()->update([
                'delegation_escorts.status' => 0,
            ]);

            $escort->delegations()->attach([
                $delegationId => [
                    'status' => 1,
                    'assigned_by' => auth()->id(),
                ],
            ]);

            $escort->delegation_id = $delegationId;
            $escort->save();

            getRoomAssignmentStatus($delegationId);

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

            $response = [
                'status' => 'success',
                'message' => __db('updated_successfully'),
                'redirect_url' => route('delegations.show', $delegationId)
            ];

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json($response);
            }

            if ($request->has('assignment_mode') && $request->assignment_mode === 'escort') {
                return redirect()->route('delegations.show', $delegationId)->with('success', __db('updated_successfully'));
            }

            return redirect()->route('delegations.show', $delegationId)->with('success', __db('updated_successfully'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Escort assignment failed: ' . $e->getMessage(), [
                'escort_id' => $escort->id,
                'delegation_id' => $delegationId,
                'user_id' => auth()->id(),
            ]);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => __db('failed_to_update')
                ], 500);
            }

            if ($request->has('assignment_mode') && $request->assignment_mode === 'escort') {
                return redirect()->route('delegations.show', $delegationId)->with('error', __db('failed_to_update'));
            }

            return redirect()->route('delegations.show', $delegationId)->with('error', __db('failed_to_update'));
        }
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

        $escort->delegation_id = null;


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

                if ($oldRoom) {

                    if (is_null($oldAssignment->room_number)) {
                        $oldRoom->assigned_rooms = max(0, $oldRoom->assigned_rooms - 1);
                        $oldRoom->save();
                    } else if ($oldRoom->assigned_rooms > 0) {
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
                }

                $oldAssignment->active_status = 0;
                $oldAssignment->save();
            }

            $escort->current_room_assignment_id = null;
        }

        getRoomAssignmentStatus($delegationId);

        $escort->save();

        return redirect()->back()->with('success', __db('updated_successfully'));
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

        try {
            $fileName = $request->file('file')->getClientOriginalName();
            Excel::import(new EscortImport($fileName), $request->file('file'));

            return redirect()->route('admin.import-logs.index', ['import_type' => 'escorts'])
                ->with('success', __db('imported_successfully'));
        } catch (\Exception $e) {
            Log::error('Escort Import Error: ' . $e->getMessage());
            return back()->with('error', __db('import_failed') . ': ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        $currentEventId = session('current_event_id', getDefaultEventId());
        $event = \App\Models\Event::find($currentEventId);

        $fileName = $event ? $event->code . '_escorts_report.xlsx' : 'escorts_report.xlsx';

        return Excel::download(new \App\Exports\EscortExport, $fileName);
    }
}
