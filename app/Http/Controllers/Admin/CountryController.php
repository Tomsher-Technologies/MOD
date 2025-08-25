<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:add_dropdown_options',  ['only' => ['index','create','store','edit','update','destroy']]);
        $this->middleware('permission:add_dropdown_options',  ['only' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Country::query();

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                  ->orWhere('short_code', 'like', "%$keyword%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status == 1) {
                $query->where('status', 1);
            } elseif ($request->status == 2) {
                $query->where('status', 0);
            }    
        }

        $countries = $query->orderBy('sort_order')->paginate(20)->appends($request->all());
        
        return view('admin.countries.index', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:countries',
            'short_code' => 'required|string|max:10|unique:countries',
            'sort_order' => 'nullable|integer',
            'flag' => 'nullable|string|max:255',
        ]);

        Country::create($request->only('name', 'short_code', 'sort_order', 'flag', 'status'));

        return back()->with('success', 'Country added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:countries,name,'.$country->id,
            'short_code' => 'required|string|max:10|unique:countries,short_code,'.$country->id,
            'sort_order' => 'nullable|integer',
            'flag' => 'nullable|string|max:255',
        ]);

        $country->update($request->only('name', 'short_code', 'sort_order', 'flag', 'status'));

        return back()->with('success', 'Country updated successfully');
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request)
    {
        $country = Country::findOrFail($request->id);
        
        $country->status = $request->status;
        $country->save();
       
        return 1;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        $country->delete();
        
        return back()->with('success', 'Country deleted successfully');
    }
}