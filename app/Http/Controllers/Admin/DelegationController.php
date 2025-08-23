<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HandlesUpdateConfirmation;
use App\Models\Delegate;
use App\Models\DelegateTransport;
use App\Models\Delegation;
use App\Models\DelegationAttachment;
use App\Models\Dropdown;
use App\Models\Interview;
use App\Models\InterviewMember;
use App\Models\OtherInterviewMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class DelegationController extends Controller
{
    use HandlesUpdateConfirmation;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:manage_delegations', [
            'only' => ['index', 'setDefault', 'search', 'members', 'editAttachment', 'updateAttachments', 'destroyAttachment']
        ]);

        $this->middleware('permission:add_delegations', [
            'only' => ['create', 'store']
        ]);

        $this->middleware('permission:edit_delegations', [
            'only' => ['edit', 'update']
        ]);

        $this->middleware('permission:view_delegations', [
            'only' => ['show']
        ]);

        $this->middleware('permission:add_delegate', [
            'only' => ['addDelegate']
        ]);

        $this->middleware('permission:delete_delegate', [
            'only' => ['destroyDelegate']
        ]);

        $this->middleware('permission:edit_delegate', [
            'only' => ['editDelegate']
        ]);

        $this->middleware('permission:add_interviews', [
            'only' => ['storeOrUpdateInterview']
        ]);

        $this->middleware('permission:edit_interviews', [
            'only' => ['editInterview', 'storeOrUpdateInterview']
        ]);

        $this->middleware('permission:delete_interviews', [
            'only' => ['destroyInterview']
        ]);

        $this->middleware('permission:add_travels', [
            'only' => ['addTravel', 'storeTravel']
        ]);

        $this->middleware('permission:view_travels', [
            'only' => ['arrivalsIndex', 'departuresIndex']
        ]);

        // $this->middleware('permission:view_interviews', [
        //     'only' => ['interviews']
        // ]);
    }

    public function index(Request $request)
    {
        $query = Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'delegates',
            'escorts'
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
        // if ($hotelName = $request->input('hotel_name')) {
        //     $query->whereHas('delegates', function ($delegateQuery) use ($hotelName) {
        //         $delegateQuery->where('hotel_name', $hotelName);
        //     });
        // }

        $delegations = $query->paginate(20);

        return view('admin.delegations.index', compact('delegations'));
    }

    public function interviewsIndex(Request $request)
    {
        // Get current event ID from session or default event
        $currentEventId = session('current_event_id', getDefaultEventId());

        $query = Interview::with([
            'delegation.continent',
            'delegation.country',
            'status',
            'interviewWithDelegation',
            'fromMembers.fromDelegate',  // explicitly load fromDelegate
            'toMembers.toDelegate',      // explicitly load toDelegate
            'toMembers.otherMember',     // also load otherMember if needed for del_others type
        ])->whereHas('delegation', function ($delegationQuery) use ($currentEventId) {
            $delegationQuery->where('event_id', $currentEventId);
        });

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('delegation', function ($delegationQuery) use ($search) {
                    $delegationQuery->where('code', 'like', "%{$search}%");
                })
                    ->orWhereHas('fromMembers.fromDelegate', function ($delegateQuery) use ($search) {
                        $delegateQuery->where('name_en', 'like', "%{$search}%")
                            ->orWhere('name_ar', 'like', "%{$search}%");
                    })
                    ->orWhereHas('toMembers.toDelegate', function ($delegateQuery) use ($search) {
                        $delegateQuery->where('name_en', 'like', "%{$search}%")
                            ->orWhere('name_ar', 'like', "%{$search}%");
                    })
                    ->orWhereHas('toMembers.otherMember', function ($otherMemberQuery) use ($search) {
                        $otherMemberQuery->where('name_en', 'like', "%{$search}%");
                    });
            });
        }

        if ($continentId = $request->input('continent_id')) {
            $query->whereHas('delegation', function ($delegationQuery) use ($continentId) {
                $delegationQuery->where('continent_id', $continentId);
            });
        }

        if ($countryId = $request->input('country_id')) {
            $query->whereHas('delegation', function ($delegationQuery) use ($countryId) {
                $delegationQuery->where('country_id', $countryId);
            });
        }

        if ($statusId = $request->input('status_id')) {
            $query->where('status_id', $statusId);
        }

        $interviews = $query->paginate(20);

        return view('admin.delegations.interviews', compact('interviews'));
    }

    public function create()
    {
        $dropdowns = $this->loadDropdownOptions();

        return view('admin.delegations.create', array_merge($dropdowns, [
            'uniqueDelegateId' => '',
        ]));
    }

    public function edit($id)
    {
        $delegation = \App\Models\Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'delegates' => function ($query) {
                $query->with(['gender', 'parent', 'delegateTransports.status']);
            },
            'attachments',
            'interviews' => function ($query) {
                $query->with([
                    'status',
                    'interviewWithDelegation',
                    'interviewMembers'
                ]);
            },
            'escorts',
            'drivers'
        ])->findOrFail($id);

        // return response()->json([
        //     'delegation' => $delegation,
        // ]);

        return view('admin.delegations.edit', compact('delegation'));
    }

    public function show($id)
    {
        $delegation = Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'delegates' => function ($query) {
                $query->with([
                    'gender',
                    'parent',
                    'delegateTransports.status',
                ]);
            },
            'attachments',
            'escorts',
            'drivers'
        ])->findOrFail($id);

        $interviews = Interview::with(['interviewMembers', 'interviewMembers.fromDelegate', 'interviewMembers.toDelegate', 'interviewWithDelegation'])
            ->where('delegation_id', $id)
            ->get();

        // return response()->json([
        //     'delegation' => $delegation,
        // ]);

        return view('admin.delegations.show', compact('delegation'));
    }

    public function arrivalsIndex(Request $request)
    {
        // Get current event ID from session or default event
        $currentEventId = session('current_event_id', getDefaultEventId());

        $query = DelegateTransport::where('type', 'arrival')
            ->with([
                'delegate.delegation.country',
                'delegate.delegation.continent',
                'delegate.delegation.escorts',
                'delegate.delegation.drivers',
                'airport',
                // 'status'
            ])->whereHas('delegate.delegation', function ($delegationQuery) use ($currentEventId) {
                $delegationQuery->where('event_id', $currentEventId);
            });

        if ($searchKey = $request->input('search_key')) {
            $query->where(function ($q) use ($searchKey) {
                $q->where('flight_no', 'like', "%{$searchKey}%")
                    ->orWhere('flight_name', 'like', "%{$searchKey}%")
                    ->orWhereHas('delegate', function ($delegateQuery) use ($searchKey) {
                        $delegateQuery->where('name_en', 'like', "%{$searchKey}%")
                            ->orWhere('name_ar', 'like', "%{$searchKey}%")
                            ->orWhereHas('delegation', function ($delegationQuery) use ($searchKey) {
                                $delegationQuery->where('code', 'like', "%{$searchKey}%");
                            });
                    });
            });
        }

        // Override with explicit event_id if provided
        if ($eventId = $request->input('event_id')) {
            $query->whereHas('delegate.delegation', function ($delegationQuery) use ($eventId) {
                $delegationQuery->where('event_id', $eventId);
            });
        }

        if ($fromDate = $request->input('from_date')) {
            $query->whereDate('date_time', '>=', $fromDate);
        }

        if ($toDate = $request->input('to_date')) {
            $query->whereDate('date_time', '<=', $toDate);
        }

        if ($continentId = $request->input('continent_id')) {
            $query->whereHas('delegate.delegation', function ($delegationQuery) use ($continentId) {
                $delegationQuery->where('continent_id', $continentId);
            });
        }

        if ($countryId = $request->input('country_id')) {
            $query->whereHas('delegate.delegation', function ($delegationQuery) use ($countryId) {
                $delegationQuery->where('country_id', $countryId);
            });
        }

        if ($airportId = $request->input('airport_id')) {
            $query->where('airport_id', $airportId);
        }

        if ($statusId = $request->input('status_id')) {
            $query->where('status_id', $statusId);
        }

        $arrivals = $query->latest()->paginate(10);


        return view('admin.arrivals.index', compact('arrivals'));
    }

    public function departuresIndex(Request $request)
    {
        // Get current event ID from session or default event
        $currentEventId = session('current_event_id', getDefaultEventId());
        
        $query = DelegateTransport::where('type', 'departure')
            ->with([
                'delegate.delegation.country',
                'delegate.delegation.continent',
                // 'delegate.escort',
                // 'delegate.driver',
                'airport',
                // 'status'
            ])->whereHas('delegate.delegation', function ($delegationQuery) use ($currentEventId) {
                $delegationQuery->where('event_id', $currentEventId);
            });

        if ($searchKey = $request->input('search_key')) {
            $query->where(function ($q) use ($searchKey) {
                $q->where('flight_no', 'like', "%{$searchKey}%")
                    ->orWhere('flight_name', 'like', "%{$searchKey}%")
                    ->orWhereHas('delegate', function ($delegateQuery) use ($searchKey) {
                        $delegateQuery->where('name_en', 'like', "%{$searchKey}%")
                            ->orWhere('name_ar', 'like', "%{$searchKey}%")
                            ->orWhereHas('delegation', function ($delegationQuery) use ($searchKey) {
                                $delegationQuery->where('code', 'like', "%{$searchKey}%");
                            });
                    });
            });
        }

        if ($eventId = $request->input('event_id')) {
            $query->whereHas('delegate.delegation', function ($delegationQuery) use ($eventId) {
                $delegationQuery->where('event_id', $eventId);
            });
        }

        if ($fromDate = $request->input('from_date')) {
            $query->whereDate('date_time', '>=', $fromDate);
        }

        if ($toDate = $request->input('to_date')) {
            $query->whereDate('date_time', '<=', $toDate);
        }

        if ($continentId = $request->input('continent_id')) {
            $query->whereHas('delegate.delegation', function ($delegationQuery) use ($continentId) {
                $delegationQuery->where('continent_id', $continentId);
            });
        }

        if ($countryId = $request->input('country_id')) {
            $query->whereHas('delegate.delegation', function ($delegationQuery) use ($countryId) {
                $delegationQuery->where('country_id', $countryId);
            });
        }

        if ($airportId = $request->input('airport_id')) {
            $query->where('airport_id', $airportId);
        }

        if ($statusId = $request->input('status_id')) {
            $query->where('status_id', $statusId);
        }

        $departures = $query->latest()->paginate(10);

        return view('admin.departures.index', compact('departures'));
    }

    public function updateTravel(Request $request, DelegateTransport $transport)
    {
        $validated = $request->validate([
            'airport_id' => 'nullable|integer|exists:dropdown_options,id',
            'flight_no' => 'nullable|string|max:255',
            'flight_name' => 'nullable|string|max:255',
            'date_time' => 'nullable|date',
            'status_id' => 'nullable|string|max:255|exists:dropdown_options,id',
        ], [
            'airport_id.exists' => __db('airport_id_exists'),
            'flight_no.max' => __db('flight_no_max', ['max' => 255]),
            'flight_name.max' => __db('flight_name_max', ['max' => 255]),
            'date_time.date' => __db('date_time_date'),
            'status_id.exists' => __db('status_id_exists'),
        ]);

        $relationsToCompare = [
            'airport_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'status_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
        ];

        $confirmationResult = $this->processUpdate($request, $transport, $validated, $relationsToCompare);

        if ($confirmationResult instanceof \Illuminate\Http\JsonResponse) {
            return $confirmationResult;
        }

        $dataToSave = $confirmationResult['data'];
        $fieldsToNotify = $confirmationResult['notify'] ?? [];

        try {
            $transport->update($dataToSave);

            // Log activity if there were changes and it was confirmed
            if ($request->has('_is_confirmed') && $request->has('changed_fields_json')) {
                $changes = json_decode($request->input('changed_fields_json'), true);
                if (!empty($changes)) {
                    $this->logActivity(
                        module: 'Travel',
                        action: 'update',
                        model: $transport,
                        changedFields: $changes,
                        submoduleId: $transport->id,
                        delegationId: $transport->delegate->delegation_id ?? null
                    );
                }
            }

            return response()->json(['status' => 'success', 'message' => 'Record updated successfully.', 'redirect_url' => $transport->type == 'arrival' ? route('delegations.arrivalsIndex') : route('delegations.departuresIndex')]);
        } catch (\Throwable $e) {
            Log::error('Travel update failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'transport_id' => $transport->id,
                'validated_data' => $validated,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to update record.'], 500);
        }
    }

    public function getTravelDetails(DelegateTransport $transport)
    {
        return response()->json([
            'success' => true,
            'data' => $transport,
        ]);
    }

    public function addTravel($id, Request $request)
    {
        $delegation = \App\Models\Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'attachments',
            'delegates.delegateTransports'
        ])->findOrFail($id);

        $showArrival = $request->query('showArrival');
        $showDeparture = $request->query('showDeparture');

        if (!$showArrival && !$showDeparture) {
            $showArrival = true;
            $showDeparture = true;
        } else {
            $showArrival = (bool) $showArrival;
            $showDeparture = (bool) $showDeparture;
        }

        $delegates = $delegation->delegates;

        if ($showArrival && !$showDeparture) {
            $delegates = $delegates->filter(function ($delegate) {
                return !$delegate->delegateTransports->contains('type', 'arrival');
            });
        } elseif ($showDeparture && !$showArrival) {
            $delegates = $delegates->filter(function ($delegate) {
                return !$delegate->delegateTransports->contains('type', 'departure');
            });
        }

        return view('admin.delegations.add-travel', compact('delegation', 'delegates', 'showArrival', 'showDeparture'));
    }

    public function addDelegate($id)
    {
        $delegation = \App\Models\Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'delegates',
        ])->findOrFail($id);

        $otherMembers = OtherInterviewMember::all();
        $delegate = new Delegate();
        // return response()->json([
        //     'delegation' => $delegation,
        // ]);

        return view('admin.delegations.add-delegate', compact('delegation', 'otherMembers', 'delegate'));
    }

    public function editDelegate(Delegation $delegation, Delegate $delegate)
    {
        return view('admin.delegations.edit-delegate', [
            'delegation' => $delegation,
            'delegate' => $delegate,
        ]);
    }

    public function addInterview(Delegation $delegation)
    {
        $otherMembers = OtherInterviewMember::all();

        return view('admin.delegations.add-interview', [
            'delegation' => $delegation,
            'interview' => new Interview(),
            'otherMembers' => $otherMembers,
        ]);
    }

    public function editInterview(Delegation $delegation, Interview $interview)
    {
        if ($interview->delegation_id !== $delegation->id) {
            abort(404, 'Interview not found for this delegation.');
        }

        $otherMembers = OtherInterviewMember::all();

        $toDelegationMembers = [];

        if ($interview->type === 'del_del' && $interview->interviewWithDelegation) {
            $toDelegationMembers = $interview->interviewWithDelegation->delegates()->get();
        }
        return view('admin.delegations.edit-interview', [
            'delegation' => $delegation,
            'interview' => $interview,
            'otherMembers' => $otherMembers,
            'toDelegationMembers' => $toDelegationMembers,
        ]);
    }

    public function store(Request $request)
    {
        // return response()->json($request->all());

        $validated = $request->validate([
            'invitation_from_id' => 'required|exists:dropdown_options,id',
            'continent_id' => 'required|exists:dropdown_options,id',
            'country_id' => 'required|exists:dropdown_options,id',
            'invitation_status_id' => 'required|exists:dropdown_options,id',
            'participation_status_id' => 'required|exists:dropdown_options,id',
            'note1' => 'nullable|string',
            'note2' => 'nullable|string',
            'delegates' => 'nullable|array',
            'delegates.*.tmp_id' => 'required_with:delegates',
            'delegates.*.title_id' => 'nullable|string',
            'delegates.*.name_ar' => 'nullable|string',
            'delegates.*.name_en' => 'required_with:delegates|string',
            'delegates.*.designation_en' => 'nullable|string',
            'delegates.*.designation_ar' => 'nullable|string',
            'delegates.*.gender_id' => 'required_with:delegates|exists:dropdown_options,id',
            'delegates.*.parent_id' => 'nullable|exists:delegates,id',
            'delegates.*.relationship' => 'nullable|string',
            'delegates.*.internal_ranking_id' => 'nullable|string',
            'delegates.*.note' => 'nullable|string',
            'delegates.*.team_head' => 'nullable|boolean',
            'delegates.*.badge_printed' => 'nullable|boolean',
            'attachments' => 'nullable|array',
            'attachments.*.title_id' => 'nullable|string',
            'attachments.*.file' => 'nullable|file|max:5120',
            'attachments.*.document_date' => 'nullable|date',
        ], [
            'invitation_from_id.required' => __db('invitation_from_id_required'),
            'invitation_from_id.exists' => __db('invitation_from_id_exists'),
            'continent_id.required' => __db('continent_id_required'),
            'continent_id.exists' => __db('continent_id_exists'),
            'country_id.required' => __db('country_id_required'),
            'country_id.exists' => __db('country_id_exists'),
            'invitation_status_id.required' => __db('invitation_status_id_required'),
            'invitation_status_id.exists' => __db('invitation_status_id_exists'),
            'participation_status_id.required' => __db('participation_status_id_required'),
            'participation_status_id.exists' => __db('participation_status_id_exists'),
            'delegates.*.tmp_id.required_with' => __db('delegates_tmp_id_required_with'),
            'delegates.*.name_en.required_with' => __db('delegates_name_en_required_with'),
            'delegates.*.gender_id.required_with' => __db('delegates_gender_id_required_with'),
            'delegates.*.gender_id.exists' => __db('delegates_gender_id_exists'),
            'delegates.*.parent_id.exists' => __db('delegates_parent_id_exists'),
            'attachments.*.title_id.exists' => __db('attachments_title_id_exists'),
            'attachments.*.file.file' => __db('attachments_file_file'),
            'attachments.*.file.max' => __db('attachments_file_max'),
            'attachments.*.document_date.date' => __db('attachments_document_date_date'),
        ]);

        DB::beginTransaction();

        try {
            $delegation = Delegation::create([
                'invitation_from_id' => $validated['invitation_from_id'],
                'continent_id' => $validated['continent_id'],
                'country_id' => $validated['country_id'],
                'invitation_status_id' => $validated['invitation_status_id'],
                'participation_status_id' => $validated['participation_status_id'],
                'note1' => $validated['note1'] ?? null,
                'note2' => $validated['note2'] ?? null,
            ]);

            if (!empty($validated['delegates'])) {
                foreach ($validated['delegates'] as $delegateData) {
                    $tmpId = $delegateData['tmp_id'];

                    unset($delegateData['parent_id']);

                    $delegateData['delegation_id'] = $delegation->id;
                    $delegateData['team_head'] = !empty($delegateData['team_head']);
                    $delegateData['badge_printed'] = !empty($delegateData['badge_printed']);

                    $createdDelegate = $delegation->delegates()->create($delegateData);
                    $tmpIdToDbId[$tmpId] = $createdDelegate->id;
                }

                foreach ($validated['delegates'] as $delegateData) {
                    if (!empty($delegateData['parent_id']) && isset($tmpIdToDbId[$delegateData['parent_id']])) {
                        $parentDbId = $tmpIdToDbId[$delegateData['parent_id']];
                        $delegateTmpId = $delegateData['tmp_id'];

                        $delegate = $delegation->delegates()->where('id', $tmpIdToDbId[$delegateTmpId])->first();
                        if ($delegate) {
                            $delegate->parent_id = $parentDbId;
                            $delegate->save();
                        }
                    }
                }
            }

            if ($request->has('attachments')) {
                foreach ($request->attachments as $idx => $attachment) {
                    if ($request->file("attachments.$idx.file")) {
                        $file = $request->file("attachments.$idx.file");
                        $path = storeUploadedFileToModuleFolder($file, 'delegations', $delegation->code, 'files') ?? '';
                        $delegation->attachments()->create([
                            'title_id' => $attachment['title_id'],
                            'file_name' => $file->getClientOriginalName(),
                            'file_path' => $path,
                            'document_date' => $attachment['document_date'] ?? now()->format('Y-m-d'),
                        ]);
                    }
                }
            }

            DB::commit();

            $this->logActivity(
                module: 'Delegation',
                action: 'create',
                model: $delegation,
                delegationId: $delegation->id
            );

            if ($request->has('submit_exit')) {
                return redirect()->route('delegations.index')->with('success', 'Delegation created.');
            } elseif ($request->has('submit_add_interview')) {
                return redirect()->route('delegations.addInterview', ['delegation_id' => $delegation->id]);
            } elseif ($request->has('submit_add_travel')) {
                return redirect()->route('delegations.addTravel', ['delegation_id' => $delegation->id]);
            }

            return redirect()->route('delegations.index')->with('success', 'Delegation created.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Delegation creation failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'validated_data' => $validated,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->withErrors(['error' => 'Failed to create delegation. Please check all required fields and try again.'])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $delegation = Delegation::findOrFail($id);

        $validated = $request->validate([
            'invitation_from_id' => 'required|exists:dropdown_options,id',
            'continent_id' => 'required|exists:dropdown_options,id',
            'country_id' => 'required|exists:dropdown_options,id',
            'invitation_status_id' => 'required|exists:dropdown_options,id',
            'participation_status_id' => 'required|exists:dropdown_options,id',
            'note1' => 'nullable|string',
            'note2' => 'nullable|string',
        ], [
            'invitation_from_id.required' => __db('invitation_from_id_required'),
            'invitation_from_id.exists' => __db('invitation_from_id_exists'),
            'continent_id.required' => __db('continent_id_required'),
            'continent_id.exists' => __db('continent_id_exists'),
            'country_id.required' => __db('country_id_required'),
            'country_id.exists' => __db('country_id_exists'),
            'invitation_status_id.required' => __db('invitation_status_id_required'),
            'invitation_status_id.exists' => __db('invitation_status_id_exists'),
            'participation_status_id.required' => __db('participation_status_id_required'),
            'participation_status_id.exists' => __db('participation_status_id_exists'),
        ]);

        // Define relations with display labels for confirmation dialog
        $relationsToCompare = [
            'invitation_from_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'continent_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'country_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'invitation_status_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'participation_status_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
        ];

        // Use the processUpdate method for confirmation dialog
        $confirmationResult = $this->processUpdate($request, $delegation, $validated, $relationsToCompare);

        if ($confirmationResult instanceof \Illuminate\Http\JsonResponse) {
            return $confirmationResult;
        }

        $dataToSave = $confirmationResult['data'];
        $fieldsToNotify = $confirmationResult['notify'] ?? [];

        try {
            DB::beginTransaction();

            $delegation->update([
                'invitation_from_id' => $dataToSave['invitation_from_id'],
                'continent_id' => $dataToSave['continent_id'],
                'country_id' => $dataToSave['country_id'],
                'invitation_status_id' => $dataToSave['invitation_status_id'],
                'participation_status_id' => $dataToSave['participation_status_id'],
                'note1' => $dataToSave['note1'] ?? null,
                'note2' => $dataToSave['note2'] ?? null,
            ]);

            DB::commit();

            // Log delegation update activity if there were changes
            if ($request->has('changed_fields_json')) {
                $changes = json_decode($request->input('changed_fields_json'), true);
                if (!empty($changes)) {
                    $this->logActivity(
                        module: 'Delegation',
                        action: 'update',
                        model: $delegation,
                        changedFields: $changes,
                        delegationId: $delegation->id
                    );
                }
            }

            if (!empty($fieldsToNotify)) {
                Log::info('Admin chose to notify about these delegation changes: ' . implode(', ', $fieldsToNotify));
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Delegation updated successfully.',
                'redirect_url' => route('delegations.show', $delegation->id),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Delegation update failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'delegation_id' => $id,
                'validated_data' => $validated,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()->withErrors(['error' => 'Failed to update delegation. Please check all required fields and try again.'])->withInput();
        }
    }

    public function updateAttachments(Request $request, $delegationId)
    {
        $delegation = Delegation::with('attachments')->findOrFail($delegationId);

        $validatedData = $request->validate([
            'attachments' => ['required', 'array'],
            'attachments.*.id' => ['nullable', 'exists:delegation_attachments,id'],
            'attachments.*.title_id' => ['required', 'exists:dropdown_options,id'],
            'attachments.*.document_date' => ['nullable', 'date'],
            'attachments.*.file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
        ], [
            'attachments.required' => __db('attachments_required'),
            'attachments.array' => __db('attachments_array'),
            'attachments.*.id.exists' => __db('delegation_attachments_id_exists'),
            'attachments.*.title_id.required' => __db('attachments_title_id_exists'),
            'attachments.*.title_id.exists' => __db('attachments_title_id_exists'),
            'attachments.*.document_date.date' => __db('attachments_document_date_date'),
            'attachments.*.file.file' => __db('attachments_file_file'),
        ]);

        $inputAttachments = $validatedData['attachments'];

        foreach ($inputAttachments as $idx => $attachmentData) {
            if (!empty($attachmentData['id']) && (!empty($attachmentData['deleted']) && $attachmentData['deleted'])) {
                $att = $delegation->attachments()->find($attachmentData['id']);
                if ($att) {
                    if ($att->file_path && Storage::disk('public')->exists($att->file_path)) {
                        Storage::disk('public')->delete($att->file_path);
                    }
                    $att->delete();
                }
                continue;
            }

            $data = [
                'title_id' => $attachmentData['title_id'],
                'document_date' => $attachmentData['document_date'] ?? now()->format('Y-m-d'),
            ];

            if ($request->hasFile("attachments.$idx.file")) {
                $file = $request->file("attachments.$idx.file");

                $path = storeUploadedFileToModuleFolder($file, 'delegations', $delegation->code, 'files') ?? '';

                $data['file_path'] = $path;
                $data['file_name'] = $file->getClientOriginalName();

                if (!empty($attachmentData['id'])) {
                    $oldAtt = $delegation->attachments()->find($attachmentData['id']);
                    if ($oldAtt && $oldAtt->file_path && Storage::disk('public')->exists($oldAtt->file_path)) {
                        Storage::disk('public')->delete($oldAtt->file_path);
                    }
                }
            }

            if (!empty($attachmentData['id'])) {
                $att = $delegation->attachments()->find($attachmentData['id']);
                if ($att) {
                    $att->update($data);
                }
            } else {
                $delegation->attachments()->create($data);
            }
        }

        return redirect()
            ->route('delegations.edit', $delegationId)
            ->with('success', 'Attachments updated successfully.');
    }

    public function destroyAttachment($id)
    {
        $attachment = DelegationAttachment::findOrFail($id);

        // Log attachment deletion activity before deletion
        $this->logActivity(
            module: 'Delegation',
            submodule: 'attachment',
            action: 'delete',
            model: $attachment,
            submoduleId: $attachment->id,
            delegationId: $attachment->delegation_id
        );

        if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return redirect()->back()->with('success', 'Attachment deleted successfully.');
    }

    public function storeTravel(Request $request, $delegationId)
    {
        $delegation = Delegation::findOrFail($delegationId);

        // return response()->json([
        //     'request' => $request->all(),
        // ]);

        $validated = $request->validate([
            'delegate_ids' => 'required|array|min:1',
            'delegate_ids.*' => 'integer|exists:delegates,id',
            'arrival.mode' => 'nullable|string|in:flight,land,sea',
            'arrival.airport_id' => 'nullable|integer|exists:dropdown_options,id',
            'arrival.flight_no' => 'nullable|string|max:255',
            'arrival.flight_name' => 'nullable|string|max:255',
            'arrival.date_time' => 'nullable|date',
            'arrival.status_id' => 'nullable|string|max:255',
            'arrival.comment' => 'nullable|string',
            'departure.mode' => 'nullable|string|in:flight,land,sea',
            'departure.airport_id' => 'nullable|integer|exists:dropdown_options,id',
            'departure.flight_no' => 'nullable|string|max:255',
            'departure.flight_name' => 'nullable|string|max:255',
            'departure.date_time' => 'nullable|date',
            'departure.status_id' => 'nullable|string|max:255',
            'departure.comment' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            foreach ($validated['delegate_ids'] as $delegateId) {
                $delegate = $delegation->delegates()->findOrFail($delegateId);

                if (isset($validated['arrival']['date_time']) && $validated['arrival']['date_time']) {
                    $delegate->delegateTransports()->create([
                        'type' => 'arrival',
                        'mode' => $validated['arrival']['mode'],
                        'airport_id' => ($validated['arrival']['mode'] ?? null) === 'flight' ? ($validated['arrival']['airport_id'] ?? null) : null,
                        'flight_no' => ($validated['arrival']['mode'] ?? null) === 'flight' ? ($validated['arrival']['flight_no'] ?? null) : null,
                        'flight_name' => ($validated['arrival']['mode'] ?? null) === 'flight' ? ($validated['arrival']['flight_name'] ?? null) : null,
                        'date_time' => $validated['arrival']['date_time'] ?? null,
                        'status_id' => $validated['arrival']['status_id'] ?? null,
                        'comment' => $validated['arrival']['comment'] ?? null,
                    ]);
                }

                if (isset($validated['departure']['date_time']) && $validated['departure']['date_time']) {
                    $delegate->delegateTransports()->create([
                        'type' => 'departure',
                        'mode' => $validated['departure']['mode'],
                        'airport_id' => ($validated['departure']['mode'] ?? null) === 'flight' ? ($validated['departure']['airport_id'] ?? null) : null,
                        'flight_no' => ($validated['departure']['mode'] ?? null) === 'flight' ? ($validated['departure']['flight_no'] ?? null) : null,
                        'flight_name' => ($validated['departure']['mode'] ?? null) === 'flight' ? ($validated['departure']['flight_name'] ?? null) : null,
                        'date_time' => $validated['departure']['date_time'] ?? null,
                        'status_id' => $validated['departure']['status_id'] ?? null,
                        'comment' => $validated['departure']['comment'] ?? null,
                    ]);
                }
            }

            DB::commit();

            $this->logActivity(
                module: 'Delegation',
                submodule: 'travel',
                action: 'create',
                model: $delegation,
                submoduleId: $delegation->id,
                delegationId: $delegation->id
            );

            return redirect()
                ->route('delegations.show', $delegationId)
                ->with('success', 'Travel details assigned to selected delegates successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Failed to save travel details: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function storeOrUpdateInterview(Request $request, Delegation $delegation, Interview $interview = null)
    {
        // return response().json([
        //     'res' => $req->all()
        // ]);

        $isEditMode = $interview && $interview->exists;

        $validator = Validator::make($request->all(), [
            'from_delegate_ids' => 'required|array|min:1',
            'from_delegate_ids.*' => 'integer|exists:delegates,id',
            'date_time' => 'required|date',
            'interview_type' => ['required', Rule::in(['delegation', 'other'])],
            'interview_with_delegation_code' => 'required_if:interview_type,delegation|nullable|string|exists:delegations,code',
            'to_delegate_id' => 'required_if:interview_type,delegation|nullable|integer|exists:delegates,id',
            'other_member_id' => 'required_if:interview_type,other|nullable|integer|exists:other_interview_members,id',
            'status_id' => 'required|integer|exists:dropdown_options,id',
            // 'comment' => 'nullable|string|max:5000',
        ], [
            'from_delegate_ids.required' => __db('from_delegate_ids_required'),
            'from_delegate_ids.array' => __db('from_delegate_ids_array'),
            'from_delegate_ids.min' => __db('from_delegate_ids_min'),
            'from_delegate_ids.*.exists' => __db('from_delegate_ids_exists'),
            'date_time.required' => __db('date_time_required'),
            'date_time.date' => __db('date_time_date'),
            'interview_type.required' => __db('interview_type_required'),
            'interview_type.in' => __db('interview_type_in'),
            'interview_with_delegation_code.required_if' => __db('interview_with_delegation_code_required_if'),
            'interview_with_delegation_code.exists' => __db('interview_with_delegation_code_exists'),
            'to_delegate_id.required_if' => __db('to_delegate_id_required_if'),
            'to_delegate_id.exists' => __db('to_delegate_id_exists'),
            'other_member_id.required_if' => __db('other_member_id_required_if'),
            'other_member_id.exists' => __db('other_member_id_exists'),
            'status_id.required' => __db('status_id_required'),
            'status_id.integer' => __db('status_id_integer'),
            'status_id.exists' => __db('status_id_exists'),
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        if ($validated['interview_type'] === 'delegation') {
            $validated['other_member_id'] = null;
        } else {
            $validated['interview_with_delegation_code'] = null;
            $validated['to_delegate_id'] = null;
        }

        $interviewWithDelegation = $validated['interview_with_delegation_code']
            ? Delegation::where('code', $validated['interview_with_delegation_code'])->first()
            : null;

        $dataToProcess = [
            'delegation_id' => $delegation->id,
            'date_time' => $validated['date_time'],
            'from_delegate_ids' => $validated['from_delegate_ids'],
            'type' => $validated['interview_type'] === 'delegation' ? 'del_del' : 'del_others',
            'interview_with' => $validated['interview_type'] === 'delegation' ? ($interviewWithDelegation->id ?? null) : null,
            'to_delegate_id' => $validated['to_delegate_id'],
            'other_member_id' => $validated['other_member_id'],
            'status_id' => $validated['status_id'],
            'comment' => $validated['comment'] ?? null,
        ];

        if (!$isEditMode) {
            try {
                DB::beginTransaction();
                $newInterview = Interview::create($dataToProcess);
                $newInterview->interviewMembers()->delete();

                foreach ($validated['from_delegate_ids'] as $fromId) {
                    $newInterview->interviewMembers()->create([
                        'member_id' => $fromId,
                        'type' => 'from'
                    ]);
                }

                // Attach new "to" member if present
                if ($validated['interview_type'] === 'delegation' && !empty($validated['to_delegate_id'])) {
                    $newInterview->interviewMembers()->create([
                        'member_id' => $validated['to_delegate_id'],
                        'type' => 'to'
                    ]);
                }

                DB::commit();

                $this->logActivity(
                    module: 'Delegation',
                    submodule: 'interview',
                    action: 'create',
                    model: $newInterview,
                    submoduleId: $newInterview->id,
                    delegationId: $delegation->id
                );

                return response()->json([
                    'status' => 'success',
                    'message' => 'Interview created successfully.',
                    'redirect_url' => route('delegations.show', $delegation->id)  // Adjust as needed
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Interview Create Failed: ' . $e->getMessage());
                return response()->json(['message' => 'Error creating interview.', 'errors' => [$e->getMessage()]], 500);
            }
        }

        $mainModelData = Arr::except($dataToProcess, ['delegation_id']);

        $relationsToCompare = [
            'from_delegate_ids' => [
                'relation' => 'fromMembers',
                'type' => 'list',
                'column' => 'member_id',
                'display_with' => [
                    'model' => \App\Models\Delegate::class,
                    'key' => 'id',
                    'label' => 'name_en',
                ],
            ],
            'to_delegate_id' => [
                'relation' => 'toMembers',
                'type' => 'single',
                'column' => 'member_id',
                'display_with' => [
                    'model' => \App\Models\Delegate::class,
                    'key' => 'id',
                    'label' => 'name_en',
                ],
            ],
            'status_id' => [
                'relation' => 'status',
                'type' => 'single',
                'column' => 'id',
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
        ];

        $confirmationResult = $this->processUpdate($request, $interview, $mainModelData, $relationsToCompare);

        if ($confirmationResult instanceof \Illuminate\Http\JsonResponse) {
            return $confirmationResult;
        }

        $dataToSave = $confirmationResult['data'];

        try {
            DB::beginTransaction();

            $interview->update($dataToSave);

            $interview->interviewMembers()->where('type', 'from')->delete();

            foreach ($validated['from_delegate_ids'] as $fromId) {
                $interview->interviewMembers()->create([
                    'member_id' => $fromId,
                    'type' => 'from',
                ]);
            }

            $interview->interviewMembers()->where('type', 'to')->delete();

            if ($validated['interview_type'] === 'delegation' && !empty($validated['to_delegate_id'])) {
                $interview->interviewMembers()->create([
                    'member_id' => $validated['to_delegate_id'],
                    'type' => 'to',
                ]);
            }

            DB::commit();

            if ($request->has('changed_fields_json')) {
                $this->logActivity(
                    module: 'Delegation',
                    submodule: 'interview',
                    action: 'update',
                    model: $interview,
                    userId: auth()->id(),
                    changedFields: json_decode($request->input('changed_fields_json'), true),
                    activityModelClass: \App\Models\DelegationActivity::class,
                    submoduleId: $interview->id,
                    delegationId: $delegation->id
                );
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Interview updated successfully.',
                'redirect_url' => route('delegations.show', $delegation->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Interview Update Failed: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating interview.'], 500);
        }
    }

    public function storeOrUpdateDelegate(Request $request, Delegation $delegation, Delegate $delegate = null)
    {
        $isEditMode = $delegate && $delegate->exists;

        // return response()->json([
        //     'isEditMode' => $isEditMode,
        //     'delegate' => $delegate,
        //     'delegation' => $delegation
        // ]);

        $validator = Validator::make($request->all(), [
            'title_id' => 'nullable|string|exists:dropdown_options,id',
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'designation_en' => 'nullable|string',
            'designation_ar' => 'nullable|string',
            'gender_id' => 'required|exists:dropdown_options,id',
            'parent_id' => 'nullable|exists:delegates,id',
            'relationship_id' => 'nullable|string|exists:dropdown_options,id',
            'internal_ranking_id' => 'nullable|string|exists:dropdown_options,id',
            'note' => 'nullable|string',
            'team_head' => 'nullable|boolean',
            'badge_printed' => 'nullable|boolean',
            'accommodation' => 'nullable|boolean',
            'arrival.mode' => 'nullable|string|in:flight,land,sea',
            'arrival.airport_id' => 'nullable|integer|exists:dropdown_options,id',
            'arrival.flight_no' => 'nullable|string|max:255',
            'arrival.flight_name' => 'nullable|string|max:255',
            'arrival.date_time' => 'nullable|date',
            'arrival.status_id' => 'nullable|string|max:255|exists:dropdown_options,id',
            'arrival.comment' => 'nullable|string',
            'departure.mode' => 'nullable|string|in:flight,land,sea',
            'departure.airport_id' => 'nullable|integer|exists:dropdown_options,id',
            'departure.flight_no' => 'nullable|string|max:255',
            'departure.flight_name' => 'nullable|string|max:255',
            'departure.date_time' => 'nullable|date',
            'departure.status_id' => 'nullable|string|max:255|exists:dropdown_options,id',
            'departure.comment' => 'nullable|string',
        ], [
            'title_id.exists' => __db('title_id_exists'),
            'name_ar.required' => __db('name_ar_required'),
            'name_en.required' => __db('name_en_required'),
            'gender_id.required' => __db('gender_id_required'),
            'gender_id.exists' => __db('delegates_gender_id_exists'),
            'parent_id.exists' => __db('delegates_parent_id_exists'),
            'relationship_id.exists' => __db('relationship_id_exists'),
            'internal_ranking_id.exists' => __db('internal_ranking_id_exists'),
            'arrival.airport_id.exists' => __db('airport_id_exists'),
            'arrival.flight_no.max' => __db('flight_no_max', ['max' => 255]),
            'arrival.flight_name.max' => __db('flight_name_max', ['max' => 255]),
            'arrival.date_time.date' => __db('date_time_date'),
            'arrival.status_id.exists' => __db('status_id_exists'),
            'departure.airport_id.exists' => __db('airport_id_exists'),
            'departure.flight_no.max' => __db('flight_no_max', ['max' => 255]),
            'departure.flight_name.max' => __db('flight_name_max', ['max' => 255]),
            'departure.date_time.date' => __db('date_time_date'),
            'departure.status_id.exists' => __db('status_id_exists'),
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        $dataToProcess = $validated;
        $dataToProcess['team_head'] = $request->has('team_head');
        $dataToProcess['badge_printed'] = $request->has('badge_printed');
        $dataToProcess['accommodation'] = $request->has('accommodation');

        if (!$isEditMode) {
            try {
                DB::beginTransaction();

                $delegateDataForCreate = Arr::except($dataToProcess, ['arrival', 'departure']);
                $newDelegate = $delegation->delegates()->create($delegateDataForCreate);

                $this->syncTransportInfo($newDelegate, $dataToProcess['arrival'] ?? null, 'arrival');
                $this->syncTransportInfo($newDelegate, $dataToProcess['departure'] ?? null, 'departure');

                DB::commit();

                // Log delegate creation activity
                $this->logActivity(
                    module: 'Delegation',
                    submodule: 'delegate',
                    action: 'create',
                    model: $newDelegate,
                    submoduleId: $newDelegate->id,
                    delegationId: $delegation->id
                );

                return response()->json([
                    'status' => 'success',
                    'message' => 'Delegate created successfully.',
                    'redirect_url' => route('delegations.edit', $delegation->id)
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Delegate Create Failed: ' . $e->getMessage() . ' on line ' . $e->getLine());
                return response()->json(['message' => 'A critical error occurred while creating the delegate.'], 500);
            }
        }

        $relationsToCompare = [
            'title_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'gender_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'parent_id' => [
                'display_with' => [
                    'model' => \App\Models\Delegate::class,
                    'key' => 'id',
                    'label' => 'name_en',
                ],
            ],
            'relationship_id' => [
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
            'arrival' => [
                'relation' => 'delegateTransports',
                'find_by' => ['type' => 'arrival'],
                'display_with' => [
                    'status_id' => [
                        'model' => \App\Models\DropdownOption::class,
                        'key' => 'id',
                        'label' => 'value',
                    ],
                    'airport_id' => [
                        'model' => \App\Models\DropdownOption::class,
                        'key' => 'id',
                        'label' => 'value',
                    ]
                ]
            ],
            'departure' => [
                'relation' => 'delegateTransports',
                'find_by' => ['type' => 'departure'],
                'display_with' => [
                    'status_id' => [
                        'model' => \App\Models\DropdownOption::class,
                        'key' => 'id',
                        'label' => 'value',
                    ],
                    'airport_id' => [
                        'model' => \App\Models\DropdownOption::class,
                        'key' => 'id',
                        'label' => 'value',
                    ]
                ]
            ]
        ];

        $confirmationResult = $this->processUpdate($request, $delegate, $dataToProcess, $relationsToCompare);

        if ($confirmationResult instanceof \Illuminate\Http\JsonResponse) {
            return $confirmationResult;
        }

        $dataToSave = $confirmationResult['data'];
        $fieldsToNotify = $confirmationResult['notify'];

        try {
            DB::beginTransaction();

            $finalDelegateData = Arr::except($dataToSave, ['arrival', 'departure']);
            $delegate->update($finalDelegateData);

            $this->syncTransportInfo($delegate, $dataToSave['arrival'] ?? null, 'arrival');
            $this->syncTransportInfo($delegate, $dataToSave['departure'] ?? null, 'departure');

            DB::commit();

            // Log delegate update activity if there were changes
            if ($request->has('changed_fields_json')) {
                $changes = json_decode($request->input('changed_fields_json'), true);
                if (!empty($changes)) {
                    $this->logActivity(
                        module: 'Delegation',
                        submodule: 'delegate',
                        action: 'update',
                        model: $delegate,
                        changedFields: $changes,
                        submoduleId: $delegate->id,
                        delegationId: $delegation->id
                    );
                }
            }

            if (!empty($fieldsToNotify)) {
                Log::info('Admin chose to notify about these changes: ' . implode(', ', $fieldsToNotify));
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Delegate updated successfully.',
                'redirect_url' => route('delegations.edit', $delegation->id),
                'fields_to_notify' => $fieldsToNotify
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delegate Save/Update Failed: ' . $e->getMessage() . ' on line ' . $e->getLine());
            return response()->json(['message' => 'A critical error occurred while saving.'], 500);
        }
    }


    public function destroyDelegate(Delegation $delegation, Delegate $delegate)
    {
        try {
            // Log delegate deletion activity before deletion
            $this->logActivity(
                module: 'Delegation',
                submodule: 'delegate',
                action: 'delete',
                model: $delegate,
                submoduleId: $delegate->id,
                delegationId: $delegation->id
            );

            $delegate->delete();

            return redirect()
                ->route('delegations.edit', $delegation->id)
                ->with('success', 'Delegate has been deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Delegate Deletion Failed: ' . $e->getMessage());

            return back()->with('error', 'An error occurred while deleting the delegate.');
        }
    }

    public function destroyInterview(Interview $interview)
    {
        try {
            $delegationId = $interview->delegation_id;

            // Log interview deletion activity before deletion
            $this->logActivity(
                module: 'Delegation',
                submodule: 'interview',
                action: 'delete',
                model: $interview,
                submoduleId: $interview->id,
                delegationId: $delegationId
            );

            $interview->interviewMembers()->delete();
            $interview->delete();

            // Always go to the delegation show route after delete
            return redirect()
                ->route('delegations.show', $delegationId)
                ->with('success', 'Interview deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Interview Delete Failed: ' . $e->getMessage());

            // Still redirect to delegation page on failure
            return redirect()
                ->route('delegations.show', $interview->delegation_id)
                ->with('error', 'Failed to delete interview.');
        }
    }

    public function searchByCode(Request $request)
    {
        $code = $request->query('code');
        if (!$code) {
            return response()->json(['success' => false, 'message' => 'Code required.']);
        }


        $currentEventId = session('current_event_id', getDefaultEventId());

        $delegation = Delegation::with('delegates')->where('code', $code)->where('event_id', $currentEventId)->first();

        if (!$delegation) {
            return response()->json(['success' => false, 'message' => 'Delegation not found.']);
        }

        $members = $delegation->delegates->map(fn($d) => [
            'id' => $d->id,
            'name_en' => $d->name_en,
        ]);

        return response()->json(['success' => true, 'members' => $members]);
    }

    public function search(Request $request)
    {
        $query = Delegation::query();

        $currentEventId = session('current_event_id', getDefaultEventId());

        $query->where('event_id', $currentEventId);

        if ($continentId = $request->query('continent_id')) {
            $query->where('continent_id', $continentId);
        }

        if ($countryId = $request->query('country_id')) {
            $query->where('country_id', $countryId);
        }

        if ($request->query('delegates') == '1') {
            $query->with(['invitationFrom', 'delegates']);
        } else {
            $query->with('invitationFrom');
        }


        $delegations = $query->with('invitationFrom', 'country', 'continent')->get();

        return response()->json([
            'success' => true,
            'delegations' => $delegations->map(fn($d) => array_merge(
                $d->toArray(),
                [
                    'id' => $d->id,
                    'code' => $d->code,
                    'invitationFrom_value' => $d->invitationFrom->value ?? '',
                ]
            )),
        ]);
    }

    public function members($delegationId)
    {
        $currentEventId = session('current_event_id', getDefaultEventId());

        $delegation = Delegation::with('delegates')->where('event_id', $currentEventId)->findOrFail($delegationId);

        $members = $delegation->delegates->map(fn($d) => [
            'id' => $d->id,
            'name_en' => $d->name_en,
        ]);

        return response()->json(['success' => true, 'members' => $members]);
    }

    protected function loadDropdownOptions()
    {
        $invitationFrom = Dropdown::with('options')->where('code', 'departments')->first();
        $continent = Dropdown::with('options')->where('code', 'continents')->first();
        $country = Dropdown::with('options')->where('code', 'country')->first();
        $invitationStatus = Dropdown::with('options')->where('code', 'invitation_status')->first();
        $participationStatus = Dropdown::with('options')->where('code', 'participation_status')->first();
        $gender = Dropdown::with('options')->where('code', 'gender')->first();

        return [
            'invitationFromOptions' => $invitationFrom ? $invitationFrom->options : collect(),
            'continentOptions' => $continent ? $continent->options : collect(),
            'countryOptions' => $country ? $country->options : collect(),
            'invitationStatusOptions' => $invitationStatus ? $invitationStatus->options : collect(),
            'participationStatusOptions' => $participationStatus ? $participationStatus->options : collect(),
            'genderOptions' => $gender ? $gender->options : collect(),
        ];
    }



    private function syncTransportInfo(Delegate $delegate, ?array $transportData, string $type): void
    {
        if (empty($transportData) || (empty($transportData['date_time']) || empty($transportData['mode']))) {
            return;
        }

        $data = [
            'mode' => $transportData['mode'] ?? null,
            'airport_id' => ($transportData['mode'] ?? null) === 'flight' ? ($transportData['airport_id'] ?? null) : null,
            'flight_no' => ($transportData['mode'] ?? null) === 'flight' ? ($transportData['flight_no'] ?? null) : null,
            'flight_name' => ($transportData['mode'] ?? null) === 'flight' ? ($transportData['flight_name'] ?? null) : null,
            'date_time' => $transportData['date_time'] ?? null,
            'status_id' => $transportData['status_id'] ?? null,
            'comment' => $transportData['comment'] ?? null,
        ];

        $delegate->delegateTransports()->updateOrCreate(['type' => $type], $data);
    }
}
