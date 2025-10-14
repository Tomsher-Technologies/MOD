<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\OtherInterviewMember;
use Illuminate\Http\Request;

class OtherMemberController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:manage_other_interview_members|delegate_manage_delegations|escort_manage_delegations|driver_manage_delegations|hotel_manage_delegations',  ['only' => ['index', 'show']]);
        $this->middleware('permission:add_other_interview_members',  ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_other_interview_members',  ['only' => ['edit', 'update']]);
    }

    public function index(Request $request)
    {
        $eventId = session('current_event_id', getDefaultEventId());

        $query = OtherInterviewMember::with('event')->orderBy('id', 'desc');

        $query->where('event_id', $eventId);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                    ->orWhere('name_ar', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            if ($status == 1) {
                $query->where('status', '1');
            } elseif ($status == 2) {
                $query->where('status', '0');
            }
        }

        $other_interview_members = $query->paginate(20) ?? collect([]);


        return view('admin.other_interview_members.index', compact('other_interview_members'));
    }



    public function create()
    {
        return view('admin.other_interview_members.create');
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $data['status'] = $request->input('status') ? '1' : '0';

        OtherInterviewMember::create($data);

        return redirect()->route('other-interview-members.index')->with('success',  __db('interview_member') . __db('created_successfully'));
    }


    public function show($id)
    {
        $id = base64_decode($id);

        $interviewMember = OtherInterviewMember::with('event')->findOrFail($id);

        return view('admin.other_interview_members.show', compact('interviewMember'));
    }

    public function edit($id)
    {
        $id = base64_decode($id);
        $interviewMember = OtherInterviewMember::find($id);
        return view('admin.other_interview_members.edit', compact('interviewMember'));
    }

    public function update(Request $request, $interviewMember)
    {

        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $member = OtherInterviewMember::find($interviewMember);
        $member->update($data);

        return redirect()->route('other-interview-members.index')->with('success', __db('interview_member') . __db('updated_successfully'));
    }
}
