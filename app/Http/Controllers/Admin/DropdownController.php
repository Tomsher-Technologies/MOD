<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dropdown;
use App\Models\DropdownOption;
use App\Http\Controllers\Controller;
use App\Imports\DropdownOptionImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class DropdownController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:manage_dropdowns',  ['only' => ['index','showOptions','setDefault']]);
        $this->middleware('permission:add_dropdown_options',  ['only' => ['bulkImport','import','storeOption']]);
        $this->middleware('permission:edit_dropdown_options',  ['only' => ['updateStatus','import','updateOption']]);
        $this->middleware('permission:view_dropdown_options',  ['only' => ['showOptions','index']]);
    }

    public function index()
    {
        $dropdowns = Dropdown::all();
        return view('admin.dropdowns.index', compact('dropdowns'));
    }

    public function showOptions(Request $request, Dropdown $dropdown)
    {
        $query = $dropdown->options();

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->Where('value', 'like', "%$keyword%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status == 1) {
                $query->where('status', 1);
            } elseif ($request->status == 2) {
                $query->where('status', 0);
            }    
        }

        $options = $query->paginate(20)->appends($request->all());
        return view('admin.dropdowns.options', compact('dropdown', 'options'));
    }

    public function countries()
    {
        // Get the Country dropdown (assuming it has code 'country')
        $countryDropdown = Dropdown::where('code', 'country')->first();
        $continentDropdown = Dropdown::where('code', 'continents')->first();
        
        if (!$countryDropdown) {
            return redirect()->back()->with('error', 'Country dropdown not found');
        }
        
        $query = $countryDropdown->options();
        $continents = $continentDropdown ? $continentDropdown->options()->where('status', 1)->get() : collect();
        
        $countries = $query->paginate(20);
        return view('admin.dropdowns.countries', compact('countries', 'continents'));
    }

    public function storeCountry(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Get the Country dropdown
        $countryDropdown = Dropdown::where('code', 'country')->first();
        
        if (!$countryDropdown) {
            return back()->with('error', 'Country dropdown not found');
        }

        // Check if country already exists
        $existingCountry = DropdownOption::where('dropdown_id', $countryDropdown->id)
            ->where('value', $request->name)
            ->first();
            
        if ($existingCountry) {
            return back()->with('error', 'Country already exists');
        }

        // Create the country option
        $country = DropdownOption::create([
            'dropdown_id' => $countryDropdown->id,
            'value' => $request->name,
            'sort_order' => 0, // You can modify this as needed
            'status' => 1
        ]);

        return back()->with('success', 'Country added successfully');
    }

    public function storeOption(Request $request)
    {
        $request->validate([
            'dropdown_id' => 'required|exists:dropdowns,id',
            'value' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        DropdownOption::create($request->only('dropdown_id', 'value', 'sort_order'));
        return back()->with('success', 'Option added successfully');
    }

    public function updateOption(Request $request, DropdownOption $option)
    {
        $option->update($request->only('value', 'sort_order', 'status'));
        return back()->with('success', 'Option updated successfully');
    }

     public function updateStatus(Request $request)
    {
        $option = DropdownOption::findOrFail($request->id);
        
        $option->status = $request->status;
        $option->save();
       
        return 1;
    }

    public function bulkImport()
    {
        return view('admin.dropdowns.bulk');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new DropdownOptionImport, $request->file('file'));

        return back()->with('success', 'Dropdown options imported successfully.');
    }
}
