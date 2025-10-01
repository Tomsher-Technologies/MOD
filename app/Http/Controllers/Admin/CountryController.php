<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Exports\CountriesExport;
use App\Imports\CountryImport;
use Maatwebsite\Excel\Facades\Excel;

class CountryController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:add_dropdown_options',  ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'updateStatus']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if export is requested
        if ($request->has('export') && $request->export == 'all') {
            return $this->exportAllCountries();
        }

        $query = Country::query();

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                    ->orWhere('name_ar', 'like', "%$keyword%")
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
        if ($request->filled('continent_id')) {
            $query->where('continent_id', $request->continent_id);
        }

        $countries = $query->with('continent')->orderBy('sort_order')->paginate(20)->appends($request->all());

        // return response()->json(['ddd' => $countries]);

        return view('admin.countries.index', compact('countries'));
    }

    /**
     * Export all countries to XLSX
     */
    public function exportAllCountries()
    {
        return Excel::download(new CountriesExport, 'countries_export_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|string|max:255|unique:countries',
                'name_ar' => 'nullable|string|max:255',
                'short_code' => 'required|string|max:10|unique:countries',
                'code' => 'string|max:10',
                'sort_order' => 'nullable|integer',
                'flag' => 'nullable|mimes:jpeg,png,jpg,webp,svg,avif|max:2048',
                'continent_id' => 'required|integer|exists:dropdown_options,id'
            ],
            [
                'name.exists' => __db('country_name_unique'),
                'short_code' => __db('short_code_unique'),
                'continent_id' => __db('continent_required')
            ]
        );

        if ($request->hasFile('flag')) {
            $data['flag'] = uploadImage('countries', $request->flag, 'country');
        }

        try {
            Country::create($data);
            return back()->with('success', __db('created_successfully'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', __db('failed_to_create') . $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:countries,name,' . $country->id,
            'name_ar' => 'nullable|string|max:255',
            'short_code' => 'required|string|max:10|unique:countries,short_code,' . $country->id,
            'code' => 'string|max:10',
            'sort_order' => 'nullable|integer',
            'flag' => 'nullable|mimes:jpeg,png,jpg,webp,svg,avif|max:2048',
            'continent_id' => 'required|integer|exists:dropdown_options,id'
        ], [
            'name.exists' => __db('country_name_unique'),
            'short_code' => __db('short_code_unique'),
            'continent_id' => __db('continent_required')
        ]);

        try {
            if ($request->hasFile('flag')) {
                $data['flag'] = uploadImage('countries', $request->file('flag'), 'country');
            } else {
                unset($data['flag']);
            }

            $country->update($data);

            return back()->with(
                'success',
                __db('updated_successfully')
            );
        } catch (\Exception $e) {
            \Log::error('Country update failed: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Failed to update country: ' . $e->getMessage());
        }
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

    public function getByContinents(Request $request)
    {
        $continentIds = $request->query('continent_ids');

        if (is_array($continentIds)) {
            $continentIds = array_filter($continentIds);
        } else if (is_string($continentIds)) {
            $continentIds = array_filter(explode(',', $continentIds));
        } else {
            $continentIds = [];
        }

        $lang = getActiveLanguage();

        $orderColumn = $lang === 'en' ? 'name' : 'name_ar';

        $countries = Country::whereIn('continent_id', $continentIds)
            ->select('id', 'name', 'name_ar')
            ->where('status', 1)
            ->orderBy($orderColumn, 'asc')
            ->get();



        return response()->json($countries);
    }

    public function destroy(Country $country)
    {
        $country->delete();

        return back()->with('success', __db('deleted_successfully'));
    }

    public function showImportForm()
    {
        return view('admin.countries.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $fileName = $request->file('file')->getClientOriginalName();
            $import = new CountryImport($fileName);
            Excel::import($import, $request->file('file'));

            return redirect()->route('admin.import-logs.index', ['import_type' => 'countries'])
                ->with('success', __db('imported_successfully'));
        } catch (\Exception $e) {
            \Log::error('Country Import Error: ' . $e->getMessage());
            return back()
                ->with('error', __db('import_failed') . $e->getMessage())
                ->withInput();
        }
    }
}
