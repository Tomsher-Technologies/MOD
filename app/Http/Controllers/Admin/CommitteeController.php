<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CommitteeMember;
use App\Models\DropdownOption;
use App\Models\Event;
use Carbon\Carbon;

class CommitteeController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:manage_committee',  ['only' => ['index']]);
        $this->middleware('permission:delete_committee',  ['only' => ['destroy']]);
        $this->middleware('permission:add_committee',  ['only' => ['create','store']]);
        $this->middleware('permission:edit_committee',  ['only' => ['edit','update','updateStatus']]);
    }

    public function index(Request $request)
    {
        $request->session()->put('committee_last_url', url()->full());
        $query = CommitteeMember::with(['designation', 'committee'])->orderBy('id','desc');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name_en', 'like', "%{$search}%")
                ->orWhere('name_ar', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('military_no', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            // 1 = active, 2 = inactive; 
            if ($request->status == 1) {
                $query->where('status', 1);
            } elseif ($request->status == 2) {
                $query->where('status', 0);
            }
        }
        if ($request->filled('designation_id')) {
            $query->where('designation_id', $request->designation_id);
        }

        if ($request->filled('committee_id')) {
            $query->where('committee_id', $request->committee_id);
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $committee_members = $query->paginate(15);

        $designations = DropdownOption::whereHas('dropdown', fn($q) => $q->where('code', 'committee_designation'))->get();
        $committees   = DropdownOption::whereHas('dropdown', fn($q) => $q->where('code', 'committee'))->get();
        $events = Event::orderBy('name_en')->get();
        return view('admin.committee.index', compact('designations','committees','committee_members','events'));
    }

    public function create()
    {
        $designations = DropdownOption::whereHas('dropdown', fn($q) => $q->where('code', 'committee_designation'))->get();
        $committees   = DropdownOption::whereHas('dropdown', fn($q) => $q->where('code', 'committee'))->get();
        $events = Event::orderBy('name_en')->get();
        return view('admin.committee.create', compact('designations','committees','events'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id'          => 'required|exists:events,id',
            'name_en'           => 'required',
            'name_ar'           => 'required',
            'email'             => 'required',
            'phone'             => 'required',
            'military_no'       => 'required',
            'designation_id'    => 'required',
            'committee_id'      => 'required',
        ],[
            'event_id.required'         => __db('this_field_is_required'),
            'name_en.required'          => __db('this_field_is_required'),
            'name_ar.required'          => __db('this_field_is_required'),
            'email.required'            => __db('this_field_is_required'),
            'phone.required'            => __db('this_field_is_required'),
            'military_no.required'      => __db('this_field_is_required'),
            'designation_id.required'   => __db('this_field_is_required'),
            'committee_id.required'     => __db('this_field_is_required'),
        ]);

        $committee = CommitteeMember::create($data);
        return redirect()->route('committees.index')->with('success',  __db('committee_member') . __db('created_successfully'));
    }

    public function edit(CommitteeMember $committee)
    {
        $designations = DropdownOption::whereHas('dropdown', fn($q) => $q->where('code', 'committee_designation'))->get();
        $committees   = DropdownOption::whereHas('dropdown', fn($q) => $q->where('code', 'committee'))->get();
        $events = Event::orderBy('name_en')->get();
        return view('admin.committee.edit', compact('committee', 'events', 'designations', 'committees'));
    }

    public function update(Request $request, CommitteeMember $committee)
    {
        $data = $request->validate([
            'event_id'          => 'required|exists:events,id',
            'name_en'           => 'required',
            'name_ar'           => 'required',
            'email'             => 'required',
            'phone'             => 'required',
            'military_no'       => 'required',
            'designation_id'    => 'required',
            'committee_id'      => 'required',
        ],[
            'event_id.required'         => __db('this_field_is_required'),
            'name_en.required'          => __db('this_field_is_required'),
            'name_ar.required'          => __db('this_field_is_required'),
            'email.required'            => __db('this_field_is_required'),
            'phone.required'            => __db('this_field_is_required'),
            'military_no.required'      => __db('this_field_is_required'),
            'designation_id.required'   => __db('this_field_is_required'),
            'committee_id.required'     => __db('this_field_is_required'),
        ]);

        $committee->update($data);

        session()->flash('success', __db('committee_member') . __db('updated_successfully'));
        return redirect()->route('committees.index');
    }

    public function destroy($id)
    {
        $committee = CommitteeMember::findOrFail($id);
        $committee->delete();
        session()->flash('success', __db('committee_member') . __db('deleted_successfully'));
        return redirect()->route('committees.index');
    }

    public function updateStatus(Request $request)
    {
        $committee = CommitteeMember::findOrFail($request->id);
        $committee->status = $request->status;
        $committee->save();
       
        return 1;
    }
}
