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
        
        // Read the last 20 lines from the log file
        $lines = $this->getLastLines($logFile, 20);
        
        return view('admin.log', ['lines' => $lines]);
    }
    
    /**
     * Get the last N lines from a file
     */
    private function getLastLines($filepath, $linesCount)
    {
        // Using tail command to efficiently get last lines
        $lines = [];
        try {
            $output = shell_exec("tail -n {$linesCount} " . escapeshellarg($filepath));
            $lines = explode("\n", rtrim($output));
        } catch (\Exception $e) {
            // Fallback method if shell_exec is disabled
            $lines = $this->getLastLinesFallback($filepath, $linesCount);
        }
        
        return $lines;
    }
    
    /**
     * Fallback method to get last lines if shell_exec is disabled
     */
    private function getLastLinesFallback($filepath, $linesCount)
    {
        $lines = [];
        $file = fopen($filepath, 'r');
        
        if ($file) {
            // Move to the end of file
            fseek($file, 0, SEEK_END);
            $pos = ftell($file);
            $lineCount = 0;
            $buffer = '';
            
            // Read file backwards
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
                
                // Handle the first line
                if ($pos == 0 && !empty($buffer)) {
                    $lines[] = $buffer;
                    break;
                }
            }
            
            fclose($file);
            
            // Reverse to get correct order
            $lines = array_reverse($lines);
        }
        
        return $lines;
    }
}