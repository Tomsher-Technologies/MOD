<?php

namespace App\Services;

use App\Models\ImportLog;

class ImportLogService
{
    public function logError($importType, $fileName, $rowNumber, $errorMessage, $rowData = null)
    {
        ImportLog::create([
            'import_type' => $importType,
            'file_name' => $fileName,
            'row_number' => $rowNumber,
            'error_message' => $errorMessage,
            'row_data' => $rowData,
            'status' => 'failed'
        ]);
    }

    public function logSuccess($importType, $fileName, $rowNumber, $rowData = null)
    {
        ImportLog::create([
            'import_type' => $importType,
            'file_name' => $fileName,
            'row_number' => $rowNumber,
            'error_message' => null,
            'row_data' => $rowData,
            'status' => 'success'
        ]);
    }

    public function clearLogs($importType = null)
    {
        if ($importType) {
            ImportLog::where('import_type', $importType)->delete();
        } else {
            ImportLog::truncate();
        }
    }

    public function getLogs($importType = null)
    {
        $query = ImportLog::orderBy('created_at', 'desc');
        
        if ($importType) {
            $query->where('import_type', $importType);
        }
        
        return $query->get();
    }
}