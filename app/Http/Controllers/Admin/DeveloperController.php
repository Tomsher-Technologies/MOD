<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DeveloperController extends Controller
{
    public function getModelData($model)
    {

        $user = Auth::user();

        if ($user && !$user->hasRole('Super Admin')) {
            abort(404);
        }

        $modelName = str_replace('-', '', ucwords($model, '-'));
        $modelClass = 'App\\Models\\' . $modelName;

        if (!class_exists($modelClass)) {
            abort(404, "Model {$modelClass} not found");
        }

        $modelInstance = new $modelClass();

        $relations = [];
        $reflection = new ReflectionClass($modelInstance);
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            if ($method->class === $modelClass) {
                $returnType = $method->getReturnType();
                if ($returnType && $returnType->getName()) {
                    $returnTypeName = $returnType->getName();
                    if (str_contains($returnTypeName, 'Relation')) {
                        $relations[] = $method->getName();
                    }
                }
            }
        }

        $query = $modelInstance->newQuery();

        if (!empty($relations)) {
            $query->with($relations);
        }

        $results = $query->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(500)
            ->get();

        return response()->json([
            'model' => $modelClass,
            'relations_found' => $relations,
            'total_records' => $results->count(),
            'data' => $results->toArray(),
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function getAllTables()
    {

        $user = Auth::user();

        if ($user && !$user->hasRole('Super Admin')) {
            abort(404);
        }

        $tables = DB::select('SHOW TABLES');

        $tableDetails = [];
        $tableKeyName = 'Tables_in_' . env('DB_DATABASE'); // MySQL default key name

        foreach ($tables as $tableRow) {
            $tableName = $tableRow->{$tableKeyName};

            if ($tableName) {
                $columns = Schema::getColumnListing($tableName);
                $rowCount = DB::table($tableName)->count();

                $tableDetails[] = [
                    'name' => $tableName,
                    'columns' => $columns,
                    'row_count' => $rowCount
                ];
            }
        }

        return response()->json([
            'total_tables' => count($tableDetails),
            'tables' => $tableDetails,
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function showLog()
    {

        $user = Auth::user();

        if ($user && !$user->hasRole('Super Admin')) {
            abort(404);
        }

        $logFile = storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {
            return view('admin.log', ['lines' => []]);
        }

        $lines = $this->getLastLines($logFile, 3000);

        return view('admin.log', ['lines' => $lines]);
    }

    private function getLastLines($filepath, $linesCount)
    {

        $user = Auth::user();

        if ($user && !$user->hasRole('Super Admin')) {
            abort(404);
        }

        $lines = [];

        if ($this->isWindows()) {
            $lines = $this->getLastLinesFallback($filepath, $linesCount);
        } else {
            try {
                $output = shell_exec("tail -n {$linesCount} " . escapeshellarg($filepath));
                if ($output !== null) {
                    $lines = explode("\n", rtrim($output));
                } else {
                    $lines = $this->getLastLinesFallback($filepath, $linesCount);
                }
            } catch (\Exception $e) {
                $lines = $this->getLastLinesFallback($filepath, $linesCount);
            }
        }

        return $lines;
    }

    private function isWindows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    private function getOSInfo()
    {
        return [
            'php_os' => PHP_OS,
            'is_windows' => $this->isWindows(),
            'uname' => php_uname(),
        ];
    }

    private function getLastLinesFallback($filepath, $linesCount)
    {

        $user = Auth::user();

        if ($user && !$user->hasRole('Super Admin')) {
            abort(404);
        }

        $lines = [];

        if (!is_readable($filepath)) {
            return ['Error: Cannot read log file. Check permissions.'];
        }

        $file = fopen($filepath, 'r');

        if (!$file) {
            return ['Error: Cannot open log file.'];
        }

        fseek($file, 0, SEEK_END);
        $fileSize = ftell($file);

        if ($fileSize == 0) {
            fclose($file);
            return ['Log file is empty.'];
        }

        $pos = $fileSize;
        $lineCount = 0;
        $buffer = '';
        $result = [];

        while ($pos > 0 && $lineCount < $linesCount) {
            $pos--;
            fseek($file, $pos, SEEK_SET);
            $char = fgetc($file);

            if ($char == "\n") {
                if (!empty(trim($buffer))) {
                    $result[] = $buffer;
                    $lineCount++;
                }
                $buffer = '';
            } else {
                $buffer = $char . $buffer;
            }
        }

        if (!empty(trim($buffer))) {
            $result[] = $buffer;
        }

        fclose($file);

        return array_reverse($result);
    }
}
