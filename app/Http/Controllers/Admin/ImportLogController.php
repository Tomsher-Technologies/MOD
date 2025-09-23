<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImportLog;
use App\Services\ImportLogService;

class ImportLogController extends Controller
{
    protected $importLogService;

    public function __construct(ImportLogService $importLogService)
    {
        $this->importLogService = $importLogService;
    }

    public function index(Request $request)
    {
        $query = ImportLog::orderBy('created_at', 'desc');
        
        if ($request->filled('import_type')) {
            $query->where('import_type', $request->import_type);
        }
        
        if ($request->filled('search')) {
            $query->where('error_message', 'like', '%' . $request->search . '%');
        }
        
        $logs = $query->paginate(20);
        $importTypes = ImportLog::select('import_type')->distinct()->get();
        
        return view('admin.imports.logs', compact('logs', 'importTypes'));
    }

    public function clearLogs(Request $request)
    {
        if ($request->filled('import_type')) {
            $this->importLogService->clearLogs($request->import_type);
        } else {
            $this->importLogService->clearLogs();
        }
        
        return back()->with('success', 'Import logs cleared successfully.');
    }
    
}
