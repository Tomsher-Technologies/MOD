<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:manage_labels',  ['only' => ['index']]);
        $this->middleware('permission:edit_labels',  ['only' => ['edit','update']]);
        $this->middleware('permission:view_labels',  ['only' => ['index']]);
    }
    public function index(Request $request)
    {
        $query = Translation::with('values');

        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('label_key', 'like', '%' . $search . '%')
                ->orWhereHas('values', function ($q2) use ($search) {
                    $q2->where('value', 'like', '%' . $search . '%');
                });
            });
        }

        $translations = $query->orderBy('id', 'desc')->paginate(30);

        return view('admin.translations.index', compact('translations'));
    }

    public function store(Request $request)
    {
        $languages = getAllActiveLanguages();

        $rules = [
            'key' => 'required|unique:translations,label_key',
        ];
        $messages = [
            'key.required' => 'The label key is required',
            'key.unique' => 'This key already exists',
        ];

        foreach ($languages as $lang) {
            $code = $lang->code;
            $rules["value_$code"] = 'required|string';

            $messages["value_{$code}.required"] = "The {$lang->name} translation is required";
            $messages["value_{$code}.string"] = "The {$lang->name} translation must be text";
        }

        $request->validate($rules, $messages);

        $translation = Translation::create([
            'label_key' => $request->key,
        ]);

        $values = [];
        foreach ($languages as $lang) {
            $values[] = [
                'lang' => $lang->code,
                'value' => $request->input('value_' . $lang->code),
            ];
        }

        $translation->values()->createMany($values);

        return response()->json(['success' => true, 'message' => __db('updated_successfully')]);
    }

    public function update(Request $request, $id)
    {
        $translation = Translation::findOrFail($id);

        $languages = getAllActiveLanguages();

        $rules = [];
        $messages = [];

        foreach ($languages as $lang) {
            $code = $lang->code;
            $rules["value_$code"] = 'required|string';

            $messages["value_{$code}.required"] = "The {$lang->name} translation is required.";
            $messages["value_{$code}.string"] = "The {$lang->name} translation must be a string.";
        }

        // $request->validate($rules, $messages);
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 201); 
        }
        
        foreach ($languages as $lang) {
            $translation->values()->updateOrCreate(
                ['lang' => $lang->code],
                ['value' => $request->input("value_{$lang->code}")]
            );
        }

        return response()->json(['success' => true, 'message' => __db('updated_successfully')]);
    }

}
