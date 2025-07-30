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
                $query->whereHas('delegates', function ($q) {
                    $q->where('team_head', true);
                });
            } elseif ($status == 0) {
                $query->whereDoesntHave('delegates', function ($q) {
                    $q->where('team_head', true);
                });
            }
        }

        $delegations = $query->paginate(20);

        return view('admin.delegations.index', compact('delegations'));
    }
    public function create()
    {
        $dropdowns = $this->loadDropdownOptions();

        return view('admin.delegations.create', $dropdowns);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'delegate_id'            => 'required|string|unique:delegations,delegate_id',
            'invitation_from_id'     => 'required|exists:dropdown_options,id',
            'continent_id'           => 'required|exists:dropdown_options,id',
            'country_id'             => 'required|exists:dropdown_options,id',
            'invitation_status_id'   => 'required|exists:dropdown_options,id',
            'participation_status_id' => 'required|exists:dropdown_options,id',
            'note1'                  => 'nullable|string',
            'note2'                  => 'nullable|string',

            'delegates'              => 'sometimes|array',
            'delegates.*.title'      => 'nullable|string',
            'delegates.*.name_en'    => 'required|string',
            'delegates.*.name_ar'    => 'required|string',
            'delegates.*.gender_id'  => 'nullable|exists:dropdown_options,id',
            'delegates.*.designation_en' => 'nullable|string',
            'delegates.*.designation_ar' => 'nullable|string',
            'delegates.*.parent_id'  => 'nullable|exists:delegates,id',
            'delegates.*.relationship' => 'nullable|string',
            'delegates.*.team_head' => 'nullable|boolean',
            'delegates.*.badge_printed' => 'nullable|boolean',
            'delegates.*.escorts'   => 'nullable|string',
            'delegates.*.drivers'   => 'nullable|string',
            'delegates.*.hotel_name' => 'nullable|string',

            'attachments'            => 'sometimes|array',
            'attachments.*.title'    => 'required|string',
            'attachments.*.file'     => 'required|file|max:5120',
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
                    $delegation->delegates()->create($delegateData);
                }
            }

            if (!empty($validated['attachments'])) {
                foreach ($validated['attachments'] as $attachmentData) {
                    $filePath = $attachmentData['file']->store('delegation_attachments');
                    $delegation->attachments()->create([
                        'title' => $attachmentData['title'],
                        'file_path' => $filePath,
                        'document_date' => $attachmentData['document_date'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('delegations.index')->with('success', 'Delegation created successfully.');
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
}
