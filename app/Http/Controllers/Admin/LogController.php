<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function showLog()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            return view('admin.log', ['lines' => []]);
        }
        
        $lines = $this->getLastLines($logFile, 3000);
        
        return view('admin.log', ['lines' => $lines]);
    }
    
    private function getLastLines($filepath, $linesCount)
    {
        $lines = [];
        try {
            $output = shell_exec("tail -n {$linesCount} " . escapeshellarg($filepath));
            $lines = explode("\n", rtrim($output));
        } catch (\Exception $e) {
            $lines = $this->getLastLinesFallback($filepath, $linesCount);
        }
        
        return $lines;
    }
    
    private function getLastLinesFallback($filepath, $linesCount)
    {
        $lines = [];
        $file = fopen($filepath, 'r');
        
        if ($file) {
            fseek($file, 0, SEEK_END);
            $pos = ftell($file);
            $lineCount = 0;
            $buffer = '';
            
            while ($pos > 0 && $lineCount < $linesCount) {
                $pos--;
                fseek($file, $pos, SEEK_SET);
                $char = fgetc($file);
                
                if ($char == "\n") {
                    if (!empty($buffer)) {
                        $lines[] = $buffer;
                        $buffer = '';
                        $lineCount++;
                    }
                } else {
                    $buffer = $char . $buffer;
                }
                
                if ($pos == 0 && !empty($buffer)) {
                    $lines[] = $buffer;
                    break;
                }
            }
            
            fclose($file);
            
            $lines = array_reverse($lines);
        }
        
        return $lines;
    }
}