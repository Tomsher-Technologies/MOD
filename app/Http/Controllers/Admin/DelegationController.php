<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delegation;
use App\Models\Dropdown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DelegationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:manage_delegations',  ['only' => ['index', 'setDefault']]);
        $this->middleware('permission:add_delegations',  ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_delegations',  ['only' => ['edit', 'update']]);
        $this->middleware('permission:view_delegations',  ['only' => ['show', 'index']]);
    }

    public function index(Request $request)
    {
        $query = Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'delegates'
        ])->orderBy('id', 'desc');

        if ($search = $request->input('search')) {
            $query->where('code', 'like', "%{$search}%");
        }
        if ($status = $request->input('status')) {
            if ($status == 1) {
                $query->whereHas('participationStatus', function ($q) {
                    $q->where('value', 'active');
                });
            } elseif ($status == 0) {
                $query->whereHas('participationStatus', function ($q) {
                    $q->where('value', 'inactive');
                });
            }
        }

        $delegations = $query->paginate(20);

        return view('admin.delegations.index', compact('delegations'));
    }
    public function create()
    {
        $delegateId = $this->generateNextDelegateId();

        $dropdowns = $this->loadDropdownOptions();

        return view('admin.delegations.create', array_merge($dropdowns, [
            'uniqueDelegateId' => $delegateId,
        ]));
    }
    public function show($id)
    {
        $delegation = \App\Models\Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'delegates' => function ($query) {
                $query->with(['gender', 'parent', 'delegateTransports']);
            },
            'attachments',
        ])->findOrFail($id);

        // $interviews = \App\Models\Interview::with('attendees')->where('delegation_id', $id)->get();

        return view('admin.delegations.show', compact('delegation'));
    }

    public function addTravel($id)
    {
        $delegation = \App\Models\Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'delegates',
            'attachments',
        ])->findOrFail($id);

        // return response()->json([
        //     'delegation' => $delegation,
        // ]);

        return view('admin.delegations.add-travel', compact('delegation'));
    }


    public function store(Request $request)
    {

        // return response()->json($request->all());

        $validated = $request->validate([
            'code' => 'required|string|unique:delegations,code',
            'invitation_from_id'     => 'required|exists:dropdown_options,id',
            'continent_id'           => 'required|exists:dropdown_options,id',
            'country_id'             => 'required|exists:dropdown_options,id',
            'invitation_status_id'   => 'required|exists:dropdown_options,id',
            'participation_status_id' => 'required|exists:dropdown_options,id',
            'note1'                  => 'nullable|string',
            'note2'                  => 'nullable|string',

            'delegates' => 'sometimes|array',
            // 'delegates.*.tmp_id' => 'required',
            'delegates.*.title_id' => 'nullable|string',
            'delegates.*.name_ar' => 'nullable|string',
            'delegates.*.name_en' => 'nullable|string',
            'delegates.*.designation_en' => 'nullable|string',
            'delegates.*.designation_ar' => 'nullable|string',
            'delegates.*.gender_id' => 'nullable|exists:dropdown_options,id',
            'delegates.*.parent_id' => 'nullable|exists:delegates,id',
            'delegates.*.relationship' => 'nullable|string',
            'delegates.*.internal_ranking_id' => 'nullable|string',
            'delegates.*.note' => 'nullable|string',
            'delegates.*.team_head' => 'nullable|boolean',
            'delegates.*.badge_printed' => 'nullable|boolean',

            'attachments'            => 'nullable|array',
            'attachments.*.title_id'    => 'nullable|string',
            'attachments.*.file'     => 'nullable|file|max:5120',
            'attachments.*.document_date' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {

            $delegation = Delegation::create([
                'code' => $validated['code'],
                'invitation_from_id' => $validated['invitation_from_id'],
                'continent_id' => $validated['continent_id'],
                'country_id' => $validated['country_id'],
                'invitation_status_id' => $validated['invitation_status_id'],
                'participation_status_id' => $validated['participation_status_id'],
                'note1' => $validated['note1'] ?? null,
                'note2' => $validated['note2'] ?? null,
            ]);

            // $tmpIdToDbId = [];

            // if (!empty($validated['delegates'])) {
            //     foreach ($validated['delegates'] as $delegateData) {
            //         $tmpId = $delegateData['tmp_id'];

            //         unset($delegateData['parent_id']);

            //         $delegateData['delegation_id'] = $delegation->id;
            //         $delegateData['team_head'] = !empty($delegateData['team_head']);
            //         $delegateData['badge_printed'] = !empty($delegateData['badge_printed']);

            //         $createdDelegate = $delegation->delegates()->create($delegateData);
            //         $tmpIdToDbId[$tmpId] = $createdDelegate->id;
            //     }

            //     foreach ($validated['delegates'] as $delegateData) {
            //         if (!empty($delegateData['parent_id']) && isset($tmpIdToDbId[$delegateData['parent_id']])) {
            //             $parentDbId = $tmpIdToDbId[$delegateData['parent_id']];
            //             $delegateTmpId = $delegateData['tmp_id'];

            //             $delegate = $delegation->delegates()->where('id', $tmpIdToDbId[$delegateTmpId])->first();
            //             if ($delegate) {
            //                 $delegate->parent_id = $parentDbId;
            //                 $delegate->save();
            //             }
            //         }
            //     }
            // }

            if (!empty($validated['delegates'])) {
                foreach ($validated['delegates'] as $delegateData) {
                    $delegateData['delegation_id'] = $delegation->id;
                    $delegateData['team_head'] = !empty($delegateData['team_head']);
                    $delegateData['badge_printed'] = !empty($delegateData['badge_printed']);
                    $delegateData['internal_ranking_id'] = $delegation->internal_ranking_id ?? null;
                    $delegation->delegates()->create($delegateData);
                }
            }

            if ($request->has('attachments')) {
                foreach ($request->attachments as $idx => $attachment) {
                    if ($request->file("attachments.$idx.file")) {
                        $file = $request->file("attachments.$idx.file");
                        $path = storeUploadedFileToModuleFolder($file, 'delegations', $delegation->code, 'files') ?? "";
                        $delegation->attachments()->create([
                            'title_id' => $attachment['title'],
                            'file_name' => $file->getClientOriginalName(),
                            'file_path' => $path,
                            'document_date' => $attachment['document_date'] ?? now()->format('Y-m-d'),
                        ]);
                    }
                }
            }


            DB::commit();

            if ($request->has('submit_and_exit')) {
                return redirect()->route('delegations.index')->with('success', 'Delegation created.');
            } elseif ($request->has('submit_add_delegate')) {
                return redirect()->route('delegates.create', ['delegation_id' => $delegation->id]);
            } elseif ($request->has('submit_add_travel')) {
                return redirect()->route('delegates.addTravel', ['delegation_id' => $delegation->id]);
            }

            return redirect()->route('delegations.index')->with('success', 'Delegation created.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create delegation: ' . $e->getMessage()])->withInput();
        }
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

            'arrival.mode' => 'required|string|in:flight,land,sea',
            'arrival.airport_id' => 'nullable|integer|exists:dropdown_options,id',
            'arrival.flight_no' => 'nullable|string|max:255',
            'arrival.flight_name' => 'nullable|string|max:255',
            'arrival.date_time' => 'nullable|date',
            'arrival.status' => 'nullable|string|max:255',
            'arrival.comment' => 'nullable|string',

            'departure.mode' => 'required|string|in:flight,land,sea',
            'departure.airport_id' => 'nullable|integer|exists:dropdown_options,id',
            'departure.flight_no' => 'nullable|string|max:255',
            'departure.flight_name' => 'nullable|string|max:255',
            'departure.date_time' => 'nullable|date',
            'departure.status' => 'nullable|string|max:255',
            'departure.comment' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            foreach ($validated['delegate_ids'] as $delegateId) {
                $delegate = $delegation->delegates()->findOrFail($delegateId);

                $delegate->delegateTransports()->create([
                    'type' => 'arrival',
                    'mode' => $validated['arrival']['mode'],
                    'airport_id' => $validated['arrival']['mode'] === 'flight' ? $validated['arrival']['airport_id'] ?? null : null,
                    'flight_no' => $validated['arrival']['mode'] === 'flight' ? $validated['arrival']['flight_no'] ?? null : null,
                    'flight_name' => $validated['arrival']['mode'] === 'flight' ? $validated['arrival']['flight_name'] ?? null : null,
                    'date_time' => $validated['arrival']['date_time'] ?? null,
                    'status_id' => $validated['arrival']['status_id'] ?? null,
                    'comment' => $validated['arrival']['comment'] ?? null,
                ]);

                $delegate->delegateTransports()->create([
                    'type' => 'departure',
                    'mode' => $validated['departure']['mode'],
                    'airport_id' => $validated['arrival']['mode'] === 'flight' ? $validated['departure']['airport_id'] ?? null : null,
                    'flight_no' => $validated['arrival']['mode'] === 'flight' ? $validated['departure']['flight_no'] ?? null : null,
                    'flight_name' => $validated['arrival']['mode'] === 'flight' ? $validated['departure']['flight_name'] ?? null : null,
                    'date_time' => $validated['departure']['date_time'] ?? null,
                    'status_id' => $validated['departure']['status_id'] ?? null,
                    'comment' => $validated['departure']['comment'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('delegations.show', $delegationId)
                ->with('success', 'Travel details assigned to selected delegates successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Failed to save travel details: ' . $e->getMessage()])
                ->withInput();
        }
    }


    protected function loadDropdownOptions()
    {
        $invitationFrom = Dropdown::with('options')->where('code', 'invitation_from')->first();
        $continent = Dropdown::with('options')->where('code', 'continent')->first();
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

    protected function generateNextDelegateId()
    {
        $lastDelegate = \App\Models\Delegation::where('code', 'like', 'DA%')
            ->orderBy('code', 'desc')
            ->first();

        if (!$lastDelegate) {
            return 'DA000001';
        }

        $lastId = $lastDelegate->code;
        $number = intval(substr($lastId, 2));

        $nextNumber = $number + 1;
        $nextDelegateId = 'DA' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        return $nextDelegateId;
    }
}
