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
            $query->where('delegate_id', 'like', "%{$search}%");
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
                $query->with(['gender', 'parent']);
            },
            'attachments',
        ])->findOrFail($id);

        // $interviews = \App\Models\Interview::with('attendees')->where('delegation_id', $id)->get();

        return view('admin.delegations.show', compact('delegation'));
    }


    public function store(Request $request)
    {

        // return response()->json($request->all());

        $validated = $request->validate([
            'delegate_id' => 'required|string|unique:delegations,delegate_id',
            'invitation_from_id'     => 'required|exists:dropdown_options,id',
            'continent_id'           => 'required|exists:dropdown_options,id',
            'country_id'             => 'required|exists:dropdown_options,id',
            'invitation_status_id'   => 'required|exists:dropdown_options,id',
            'participation_status_id' => 'required|exists:dropdown_options,id',
            'note1'                  => 'nullable|string',
            'note2'                  => 'nullable|string',

            'delegates' => 'sometimes|array',
            'delegates.*.title' => 'nullable|string',
            'delegates.*.name_ar' => 'nullable|string',
            'delegates.*.name_en' => 'nullable|string',
            'delegates.*.designation_en' => 'nullable|string',
            'delegates.*.designation_ar' => 'nullable|string',
            'delegates.*.gender_id' => 'nullable|exists:dropdown_options,id',
            'delegates.*.parent_id' => 'nullable|exists:delegates,id',
            'delegates.*.relationship' => 'nullable|string',
            'delegates.*.internal_ranking' => 'nullable|string',
            'delegates.*.note' => 'nullable|string',
            'delegates.*.team_head' => 'nullable|boolean',
            'delegates.*.badge_printed' => 'nullable|boolean',

            'attachments'            => 'nullable|array',
            'attachments.*.title'    => 'nullable|string',
            'attachments.*.file'     => 'nullable|file|max:5120',
            'attachments.*.document_date' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {

            $delegation = Delegation::create([
                'delegate_id' => $validated['delegate_id'],
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
                    $delegateData['delegation_id'] = $delegation->id;
                    $delegateData['team_head'] = !empty($delegateData['team_head']);
                    $delegateData['badge_printed'] = !empty($delegateData['badge_printed']);
                    $delegation->delegates()->create($delegateData);
                }
            }

            if ($request->has('attachments')) {
                foreach ($request->attachments as $idx => $attachment) {
                    if ($request->file("attachments.$idx.file")) {
                        $file = $request->file("attachments.$idx.file");
                        $path = storeUploadedFileToModuleFolder($file, 'delegations', $delegation->delegate_id, 'files') ?? "";
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
            } elseif ($request->has('submit_add_flight')) {
                return redirect()->route('flights.create', ['delegation_id' => $delegation->id]);
            }

            return redirect()->route('delegations.index')->with('success', 'Delegation created.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create delegation: ' . $e->getMessage()])->withInput();
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
        $lastDelegate = \App\Models\Delegation::where('delegate_id', 'like', 'DA%')
            ->orderBy('delegate_id', 'desc')
            ->first();

        if (!$lastDelegate) {
            return 'DA000001';
        }

        $lastId = $lastDelegate->delegate_id;
        $number = intval(substr($lastId, 2));

        $nextNumber = $number + 1;
        $nextDelegateId = 'DA' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        return $nextDelegateId;
    }
}
