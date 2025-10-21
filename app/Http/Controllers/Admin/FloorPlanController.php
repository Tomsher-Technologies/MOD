<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\FloorPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FloorPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage_floor_plans', ['only' => ['index']]);
        $this->middleware('permission:view_floor_plans', ['only' => ['show']]);
        $this->middleware('permission:add_floor_plans', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_floor_plans', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_floor_plans', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = FloorPlan::query();

        $currentEventId = session('current_event_id', getDefaultEventId());
        $query->where('event_id', $currentEventId);

        if ($eventId = $request->input('event_id')) {
            $query->where('event_id', $eventId);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title_en', 'like', "%{$search}%")
                    ->orWhere('title_ar', 'like', "%{$search}%");
            });
        }

        $floorPlans = $query->orderBy('created_at', 'desc')->paginate(20);
        $events = Event::all();

        return view('admin.floor-plans.index', compact('floorPlans', 'events'));
    }

    public function create()
    {
        $events = Event::all();
        return view('admin.floor-plans.create', compact('events'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'floor_plan_files.*' => 'required|file|max:10240',
            'file_titles.*' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $fileObjects = [];
        $files = $request->file('floor_plan_files', []);
        $fileTitles = $request->input('file_titles', []);
        
        foreach ($files as $index => $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }
            
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('floor-plans', $fileName, 'public');
            
            // Get the custom title if provided, otherwise use the original filename
            $fileTitle = !empty($fileTitles[$index]) ? $fileTitles[$index] : basename($filePath);
            
            $fileObjects[] = [
                'path' => $filePath,
                'title' => $fileTitle
            ];
        }

        FloorPlan::create([
            'event_id' => $request->event_id,
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'file_paths' => $fileObjects,
        ]);

        return redirect()->route('floor-plans.index')->with('success', __db('created_successfully'));
    }

    public function show(FloorPlan $floorPlan)
    {
        return view('admin.floor-plans.show', compact('floorPlan'));
    }

    public function edit(FloorPlan $floorPlan)
    {
        $events = Event::all();
        return view('admin.floor-plans.edit', compact('floorPlan', 'events'));
    }

    public function update(Request $request, FloorPlan $floorPlan)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'existing_file_paths' => 'array',
            'existing_file_paths.*.path' => 'string',
            'existing_file_paths.*.title' => 'string|max:255',
            'new_floor_plan_files.*' => 'nullable|file|max:10240',
            'new_file_titles.*' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'event_id' => $request->event_id,
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
        ];

        $originalFileObjects = $floorPlan->file_paths ?? [];
        
        // Get existing files with updated titles
        $keptFileObjects = [];
        $existingFiles = $request->input('existing_file_paths', []);
        
        foreach ($existingFiles as $existingFile) {
            $keptFileObjects[] = [
                'path' => $existingFile['path'],
                'title' => !empty($existingFile['title']) ? $existingFile['title'] : basename($existingFile['path'])
            ];
        }

        // Add new files if any
        if ($request->hasFile('new_floor_plan_files')) {
            $newFiles = $request->file('new_floor_plan_files');
            $newFileTitles = $request->input('new_file_titles', []);
            
            foreach ($newFiles as $index => $file) {
                if ($file && $file->isValid()) {
                    $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $filePath = $file->storeAs('floor-plans', $fileName, 'public');
                    
                    // Get the custom title if provided, otherwise use the original filename
                    $fileTitle = !empty($newFileTitles[$index]) ? $newFileTitles[$index] : basename($filePath);
                    
                    $keptFileObjects[] = [
                        'path' => $filePath,
                        'title' => $fileTitle
                    ];
                }
            }
        }

        $data['file_paths'] = array_values($keptFileObjects);
        $floorPlan->update($data);

        // Handle file deletion - find files to delete by comparing original vs updated
        $originalPaths = [];
        foreach ($originalFileObjects as $fileObj) {
            if (is_string($fileObj)) {
                // Old format - just the path as a string
                $originalPaths[] = $fileObj;
            } else {
                // New format - object with path and title
                $originalPaths[] = $fileObj['path'];
            }
        }
        
        $updatedPaths = array_column($keptFileObjects, 'path');
        
        $pathsToDelete = array_diff($originalPaths, $updatedPaths);
        
        foreach ($pathsToDelete as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        return redirect()->route('floor-plans.index')->with('success', __db('updated_successfully'));
    }


    public function destroy(FloorPlan $floorPlan)
    {
        foreach ($floorPlan->file_paths ?? [] as $filePath) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }
        $floorPlan->delete();

        return redirect()->route('floor-plans.index')->with('success', __db('deleted_successfully'));
    }
}
