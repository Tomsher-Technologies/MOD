<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delegate;
use App\Models\Delegation;
use App\Models\DelegationAttachment;
use App\Models\Dropdown;
use App\Models\Interview;
use App\Models\InterviewMember;
use App\Models\OtherInterviewMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DelegationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:manage_delegations', [
            'only' => ['index', 'setDefault', 'search', 'members', 'editAttachment']
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

        $this->middleware('permission:add_interviews', [
            'only' => ['addInterview', 'storeInterview']
        ]);

        $this->middleware('permission:add_travels', [
            'only' => ['addTravel', 'storeTravel']
        ]);
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

    public function edit($id)
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
            'interviews' => function ($query) {
                $query->with([
                    'status',
                    'interviewWithDelegation',
                    'interviewMembers'
                ]);
            }
        ])->findOrFail($id);
        // return response()->json([
        //     'delegation' => $delegation,
        //     'nextDelegateId' => $this->generateNextDelegateId(),
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
                    'delegateTransports',
                ]);
            },
            'attachments',
        ])->findOrFail($id);

        $interviews = Interview::with(['interviewMembers', 'interviewMembers.fromDelegate', 'interviewMembers.toDelegate', 'interviewWithDelegation'])
            ->where('delegation_id', $id)
            ->get();


        // return response()->json([
        //     'delegation' => $delegation,
        //     'ssss' => $interviews,
        // ]);

        return view('admin.delegations.show', compact('delegation'));
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

    public function addInterview($id)
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

        // return response()->json([
        //     'delegation' => $delegation,
        // ]);

        return view('admin.delegations.add-interview', compact('delegation', 'otherMembers'));
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

        // return response()->json([
        //     'delegation' => $delegation,
        // ]);

        return view('admin.delegations.add-delegate', compact('delegation', 'otherMembers'));
    }

    public function editAttachment($id)
    {

        return view('admin.delegations.edit-attachment');
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
            'delegates.*.tmp_id' => 'required',
            'delegates.*.title_id' => 'nullable|string',
            'delegates.*.name_ar' => 'nullable|string',
            'delegates.*.name_en' => 'required|string',
            'delegates.*.designation_en' => 'nullable|string',
            'delegates.*.designation_ar' => 'nullable|string',
            'delegates.*.gender_id' => 'required|exists:dropdown_options,id',
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

            // if (!empty($validated['delegates'])) {
            //     foreach ($validated['delegates'] as $delegateData) {
            //         $delegateData['delegation_id'] = $delegation->id;
            //         $delegateData['team_head'] = !empty($delegateData['team_head']);
            //         $delegateData['badge_printed'] = !empty($delegateData['badge_printed']);
            //         $delegateData['internal_ranking_id'] = $delegation->internal_ranking_id ?? null;
            //         $delegation->delegates()->create($delegateData);
            //     }
            // }

            if ($request->has('attachments')) {
                foreach ($request->attachments as $idx => $attachment) {
                    if ($request->file("attachments.$idx.file")) {
                        $file = $request->file("attachments.$idx.file");
                        $path = storeUploadedFileToModuleFolder($file, 'delegations', $delegation->code, 'files') ?? "";
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
            return back()->withErrors(['error' => 'Failed to create delegation: ' . $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $delegation = Delegation::findOrFail($id);

        $validated = $request->validate([
            'invitation_from_id'      => 'required|exists:dropdown_options,id',
            'continent_id'            => 'required|exists:dropdown_options,id',
            'country_id'              => 'required|exists:dropdown_options,id',
            'invitation_status_id'    => 'required|exists:dropdown_options,id',
            'participation_status_id' => 'required|exists:dropdown_options,id',
            'note1'                   => 'nullable|string',
            'note2'                   => 'nullable|string',
        ]);

        try {
            $delegation->update([
                'invitation_from_id'    => $validated['invitation_from_id'],
                'continent_id'          => $validated['continent_id'],
                'country_id'            => $validated['country_id'],
                'invitation_status_id'  => $validated['invitation_status_id'],
                'participation_status_id' => $validated['participation_status_id'],
                'note1'                 => $validated['note1'] ?? null,
                'note2'                 => $validated['note2'] ?? null,
            ]);

            return redirect()->route('delegations.index')->with('success', 'Delegation updated successfully.');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Failed to update delegation: ' . $e->getMessage()])->withInput();
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
        ]);

        $inputAttachments = $validatedData['attachments'];

        foreach ($inputAttachments as $idx => $attachmentData) {

            if (!empty($attachmentData['id']) && $attachmentData['deleted']) {
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

                $path = storeUploadedFileToModuleFolder($file, 'delegations', $delegation->code, 'files') ?? "";

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

        return redirect()->route('delegations.edit', $delegationId)
            ->with('success', 'Attachments updated successfully.');
    }


    public function destroyAttachment($id)
    {
        $attachment = DelegationAttachment::findOrFail($id);

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

                if (isset($validated['arrival']['status_id'])) {
                    $delegate->delegateTransports()->create([
                        'type' => 'arrival',
                        'mode' => $validated['arrival']['mode'],
                        'airport_id' => $validated['arrival']['mode'] === 'flight' ? $validated['arrival']['airport_id'] : null,
                        'flight_no' => $validated['arrival']['mode'] === 'flight' ? $validated['arrival']['flight_no'] : null,
                        'flight_name' => $validated['arrival']['mode'] === 'flight' ? $validated['arrival']['flight_name'] : null,
                        'date_time' => $validated['arrival']['date_time'] ?? null,
                        'status_id' => $validated['arrival']['status_id'] ?? null,
                        'comment' => $validated['arrival']['comment'] ?? null,
                    ]);
                }

                if (isset($validated['departure']['status_id'])) {
                    $delegate->delegateTransports()->create([
                        'type' => 'departure',
                        'mode' => $validated['departure']['mode'],
                        'airport_id' => $validated['arrival']['mode'] === 'flight' ? $validated['departure']['airport_id'] : null,
                        'flight_no' => $validated['arrival']['mode'] === 'flight' ? $validated['departure']['flight_no'] : null,
                        'flight_name' => $validated['arrival']['mode'] === 'flight' ? $validated['departure']['flight_name'] : null,
                        'date_time' => $validated['departure']['date_time'] ?? null,
                        'status_id' => $validated['departure']['status_id'] ?? null,
                        'comment' => $validated['departure']['comment'] ?? null,
                    ]);
                }
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

    public function storeInterview(Request $request, $delegationId)
    {

        // return response()->json($request->all());

        $delegation = Delegation::findOrFail($delegationId);

        $validator = Validator::make($request->all(), [
            'delegate_ids' => 'required|array|min:1',
            'delegate_ids.*' => 'integer|exists:delegates,id',
            'date_time' => 'required|date',
            'interview_type' => ['required', Rule::in(['delegation', 'other'])],
            'interview_with_delegation' => 'required_if:interview_type,delegation|string|nullable|exists:delegations,code',
            'interview_with_other_member_id' => 'required_if:interview_type,other|string|nullable|exists:other_interview_members,id',
            'members' => 'required_if:interview_type,delegation|string|nullable',
            'members.*' => 'integer|exists:delegates,id',
            'status' => 'required|string|max:50',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        $interviewWithDelegationId = null;
        if ($data['interview_type'] === 'delegation') {
            $interviewWithdelegation = Delegation::where('code', $data['interview_with_delegation'])->first();
            $interviewWithDelegationId = $interviewWithdelegation ? $interviewWithdelegation->id : null;
        }

        $interview = Interview::create([
            'delegation_id' => $delegation->id,
            'type' => $data['interview_type'] === 'delegation' ? 'del_del' : 'del_others',
            'interview_with' => $data['interview_type'] === 'delegation' ? $interviewWithDelegationId : null,
            'other_member_id' => $data['interview_type'] === 'other' ? $data['interview_with_other_member_id'] : null,
            'date_time' => $data['date_time'],
            'status_id' => $data['status'],
            'comment' => $data['comment'] ?? null,
        ]);

        foreach ($data['delegate_ids'] as $delegateId) {
            InterviewMember::create([
                'interview_id' => $interview->id,
                'type' => 'from',
                'member_id' => $delegateId,
            ]);
        }

        if ($data['interview_type'] === 'delegation' && !empty($data['members'])) {
            InterviewMember::create([
                'interview_id' => $interview->id,
                'type' => 'to',
                'member_id' => $data['members'],
            ]);
        }

        if ($request->has('submit_exit')) {
            return redirect()->route('delegations.index')->with('success', 'Interview created.');
        } elseif ($request->has('submit_add_new')) {
            return redirect()->route('delegations.addInterview', ['id' => $delegationId])->with('success', 'Interview created.');
        } elseif ($request->has('submit_add_travel')) {
            return redirect()->route('delegations.addTravel', ['id' => $delegationId])->with('success', 'Interview created.');
        }

        return redirect()->route('delegations.show', $delegation->id)
            ->with('success', 'Interview added successfully.');
    }

    public function storeDelegate(Request $request, $delegationId)
    {
        $validated = $request->validate([
            'title_id' => 'nullable|string|exists:dropdown_options,id',
            'name_ar' => 'nullable|string',
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
        ]);

        $validated['team_head'] = !empty($validated['team_head']);
        $validated['badge_printed'] = !empty($validated['badge_printed']);
        $validated['accommodation'] = !empty($validated['accommodation']);

        $delegation = Delegation::findOrFail($delegationId);
        $delegate = $delegation->delegates()->create($validated);

        if (isset($validated['arrival']['status_id'])) {
            $delegate->delegateTransports()->create([
                'type' => 'arrival',
                'mode' => $validated['arrival']['mode'],
                'airport_id' => $validated['arrival']['mode'] === 'flight' ? $validated['arrival']['airport_id'] : null,
                'flight_no' => $validated['arrival']['mode'] === 'flight' ? $validated['arrival']['flight_no'] : null,
                'flight_name' => $validated['arrival']['mode'] === 'flight' ? $validated['arrival']['flight_name'] : null,
                'date_time' => $validated['arrival']['date_time'] ?? null,
                'status_id' => $validated['arrival']['status_id'] ?? null,
                'comment' => $validated['arrival']['comment'] ?? null,
            ]);
        }

        if (isset($validated['departure']['status_id'])) {
            $delegate->delegateTransports()->create([
                'type' => 'departure',
                'mode' => $validated['departure']['mode'],
                'airport_id' => $validated['arrival']['mode'] === 'flight' ? $validated['departure']['airport_id'] : null,
                'flight_no' => $validated['arrival']['mode'] === 'flight' ? $validated['departure']['flight_no'] : null,
                'flight_name' => $validated['arrival']['mode'] === 'flight' ? $validated['departure']['flight_name'] : null,
                'date_time' => $validated['departure']['date_time'] ?? null,
                'status_id' => $validated['departure']['status_id'] ?? null,
                'comment' => $validated['departure']['comment'] ?? null,
            ]);
        }

        return redirect()->route('delegations.edit', $delegationId)->with('success', 'Delegation created.');
    }

    public function deleteDelegate($delegationId, $delegateId)
    {
        $delegation = Delegation::findOrFail($delegationId);
        $delegate = $delegation->delegates()->findOrFail($delegateId);

        $delegate->delete();

        return redirect()->route('delegations.edit', $delegationId)->with('success', 'Delegate deleted successfully.');
    }

    public function searchByCode(Request $request)
    {

        $code = $request->query('code');
        if (!$code) {
            return response()->json(['success' => false, 'message' => 'Code required.']);
        }

        $delegation = Delegation::with('delegates')->where('code', $code)->first();

        if (!$delegation) {
            return response()->json(['success' => false, 'message' => 'Delegation not found.']);
        }

        return response()->json(['success' => true, 'delegation' => [
            'id' => $delegation->id,
            'code' => $delegation->code,
            'delegates' => $delegation->delegates->map(fn($d) => ['id' => $d->id, 'value_en' => $d->value_en]),
        ]]);
    }

    public function search(Request $request)
    {
        $query = Delegation::query();

        if ($continentId = $request->query('continent_id')) {
            $query->where('continent_id', $continentId);
        }

        if ($countryId = $request->query('country_id')) {
            $query->where('country_id', $countryId);
        }

        $delegations = $query->with('invitationFrom')->get();

        return response()->json([
            'success' => true,
            'delegations' => $delegations->map(fn($d) => [
                'id' => $d->id,
                'code' => $d->code,
                'invitationFrom_value' => $d->invitationFrom->value ?? '',
            ]),
        ]);
    }

    public function members($delegationId)
    {
        $delegation = Delegation::with('delegates')->findOrFail($delegationId);

        $members = $delegation->delegates->map(fn($d) => [
            'id' => $d->id,
            'name_en' => $d->name_en,
        ]);

        return response()->json(['success' => true, 'members' => $members]);
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
