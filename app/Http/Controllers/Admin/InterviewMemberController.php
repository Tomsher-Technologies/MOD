<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\InterviewMember;
use Illuminate\Http\Request;

class InterviewMemberController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:manage_interview_members',  ['only' => ['index']]);
        $this->middleware('permission:add_interview_members',  ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_interview_members',  ['only' => ['edit', 'update']]);
        $this->middleware('permission:view_interview_members',  ['only' => ['show', 'index']]);
    }
}
