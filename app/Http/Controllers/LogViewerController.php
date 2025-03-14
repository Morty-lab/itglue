<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class LogViewerController extends Controller
{
    public function index()
    {
        // Only allow admins to view logs
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }

        $logPath = storage_path('logs/laravel.log');

        if (!File::exists($logPath)) {
            return view('admin.logs', [
                'logContent' => 'Log file not found: ' . $logPath,
                'error' => true
            ]);
        }

        try {
            // Get the last 1000 lines (adjust as needed)
            $logContent = $this->getLastLines($logPath, 1000);

            // Highlight errors for easier visibility
            $logContent = $this->highlightErrors($logContent);

            return view('admin.logs', [
                'logContent' => $logContent,
                'error' => false
            ]);
        } catch (\Exception $e) {
            return view('admin.logs', [
                'logContent' => 'Error reading log file: ' . $e->getMessage(),
                'error' => true
            ]);
        }
    }

    /**
     * Get the last N lines of a file
     */
    private function getLastLines($filePath, $lines = 1000)
    {
        $file = File::get($filePath);
        $fileArray = explode("\n", $file);

        // Get the last N lines
        $fileArray = array_slice($fileArray, -$lines);

        // Reverse the array to show newest logs at the top
        $fileArray = array_reverse($fileArray);

        return implode("\n", $fileArray);
    }

    /**
     * Highlight errors in the log content
     */
    private function highlightErrors($content)
    {
        // Highlight ERROR level logs in red
        $content = preg_replace('/(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?ERROR.*?)(\n|$)/i', '<span style="color: red;">$1</span>$2', $content);

        // Highlight WARNING level logs in orange
        $content = preg_replace('/(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?WARNING.*?)(\n|$)/i', '<span style="color: orange;">$1</span>$2', $content);

        // Highlight INFO level logs in blue
        $content = preg_replace('/(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?INFO.*?)(\n|$)/i', '<span style="color: blue;">$1</span>$2', $content);

        return $content;
    }
}
