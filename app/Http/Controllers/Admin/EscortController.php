<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HandlesUpdateConfirmation;
use App\Models\Delegation;
use App\Models\Dropdown;
use App\Models\Escort;
use Illuminate\Http\Request;

class EscortController extends Controller
{
    use HandlesUpdateConfirmation;

    public function __construct()
    {
        $this->middleware('auth');

        // $this->middleware('permission:manage_escorts', [
        //     'only' => ['index', 'search']
        // ]);

        // $this->middleware('permission:add_escorts', [
        //     'only' => ['create', 'store']
        // ]);

        // $this->middleware('permission:edit_escorts', [
        //     'only' => ['edit', 'update']
        // ]);

        // $this->middleware('permission:delete_escorts', [
        //     'only' => ['destroy']
        // ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Escort::with('delegation', 'gender', 'nationality')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                    ->orWhere('name_ar', 'like', "%{$search}%")
                    ->orWhere('military_number', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('delegation', function ($delegationQuery) use ($search) {
                        $delegationQuery->where('code', 'like', "%{$search}%");
                    });
            });
        }

        $escorts = $query->paginate(10);
        return view('admin.escorts.index', compact('escorts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $delegations = Delegation::all();
        $dropdowns = $this->loadDropdownOptions();

        return view('admin.escorts.create', compact('delegations', 'dropdowns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'delegation_id' => 'nullable|exists:delegations,id',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'gender_id' => 'nullable|exists:dropdown_options,id',
            'nationality_id' => 'nullable|exists:dropdown_options,id',
            'date_of_birth' => 'nullable|date',
            'id_number' => 'nullable|string|max:255',
            'id_issue_date' => 'nullable|date',
            'id_expiry_date' => 'nullable|date',
        ]);

        $escort = Escort::create($request->all());

        // Log activity
        $this->logActivity('Escort', $escort, 'create');

        return redirect()->route('escorts.index')->with('success', __db('Escort created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $escort = Escort::with('delegation', 'gender', 'nationality')->findOrFail($id);
        return view('admin.escorts.show', compact('escort'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $escort = Escort::findOrFail($id);
        $delegations = Delegation::all();
        $dropdowns = $this->loadDropdownOptions();

        return view('admin.escorts.edit', compact('escort', 'delegations', 'dropdowns'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'delegation_id' => 'nullable|exists:delegations,id',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'gender_id' => 'nullable|exists:dropdown_options,id',
            'nationality_id' => 'nullable|exists:dropdown_options,id',
            'date_of_birth' => 'nullable|date',
            'id_number' => 'nullable|string|max:255',
            'id_issue_date' => 'nullable|date',
            'id_expiry_date' => 'nullable|date',
        ]);

        $escort = Escort::findOrFail($id);
        $escort->update($request->all());

        // Log activity
        $this->logActivity('Escort', $escort, 'update');

        return redirect()->route('escorts.index')->with('success', __db('Escort updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $escort = Escort::findOrFail($id);
        $escort->delete();

        // Log activity
        $this->logActivity('Escort', $escort, 'delete');

        return redirect()->route('escorts.index')->with('success', __db('Escort deleted successfully.'));
    }

    protected function loadDropdownOptions()
    {
        $gender = Dropdown::with('options')->where('code', 'gender')->first();
        $nationality = Dropdown::with('options')->where('code', 'nationality')->first();

        return [
            'genders' => $gender ? $gender->options : collect(),
            'nationalities' => $nationality ? $nationality->options : collect(),
        ];
    }
}
