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
