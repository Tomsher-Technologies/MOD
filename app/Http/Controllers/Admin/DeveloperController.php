<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DeveloperController extends Controller
{
    public function getModelData($model)
    {
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
}