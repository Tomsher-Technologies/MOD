<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        if ($request->filled('continent_id')) {
            $query->where('continent_id', $request->continent_id);
        }

        $countries = $query->with('continent')->orderBy('sort_order')->paginate(20)->appends($request->all());

        // return response()->json(['ddd' => $countries]);

        return view('admin.countries.index', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|string|max:255|unique:countries',
                'short_code' => 'required|string|max:10|unique:countries',
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
            return back()->with('success', 'Country added successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create country: ' . $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:countries,name,' . $country->id,
            'short_code' => 'required|string|max:10|unique:countries,short_code,' . $country->id,
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

            return back()->with('success', 'Country updated successfully');
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

        $countries = Country::whereIn('continent_id', $continentIds)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($countries);
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
