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
        $dropdowns = Dropdown::where('status', 1)->get();
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

    public function storeOption(Request $request)
    {
        $request->validate([
            'dropdown_id' => 'required|exists:dropdowns,id',
            'value' => 'required|string',
            'value_ar' => 'nullable|string',
            'code' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        DropdownOption::create($request->only('dropdown_id', 'value', 'value_ar', 'code', 'sort_order'));
        return back()->with('success', 'Option added successfully');
    }

    public function updateOption(Request $request, DropdownOption $option)
    {
        $option->update($request->only('value', 'value_ar', 'code', 'sort_order', 'status'));
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
    
    public function export()
    {
        $dropdowns = Dropdown::with('options')->get();
        
        $data = [];
        foreach ($dropdowns as $dropdown) {
            foreach ($dropdown->options as $option) {
                $data[] = [
                    $dropdown->id,
                    $dropdown->name,
                    $option->id,
                    $option->value,
                    $option->value_ar,
                    $option->code,
                    $option->sort_order,
                    $option->status,
                ];
            }
        }
        
        $filename = 'dropdowns_export_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new \App\Exports\DropdownExport($data), $filename);
    }
}
