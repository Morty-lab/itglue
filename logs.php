<?php
// Create this as a standalone PHP file in your public directory
// Name it logs.php and access it at yourdomain.com/logs.php

// Log file path
$logPath = '/var/www/clients/client3/web65/web/storage/logs/laravel.log';

// Handle log clearing if requested via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear']) && $_POST['clear'] === '1') {
    // Truncate the log file by opening it in write mode
    if (file_exists($logPath)) {
        file_put_contents($logPath, "Log file cleared at " . date('Y-m-d H:i:s') . "\n");
        $message = "Log file has been cleared successfully.";
    } else {
        $message = "Log file not found.";
    }
} else {
    $message = "";
}

// Check if log file exists
if (!file_exists($logPath)) {
    $logs = "Log file not found: " . $logPath;
    $fileSize = 0;
} else {
    // Get the file size for display
    $fileSize = round(filesize($logPath) / 1024 / 1024, 2); // in MB

    // Get the last 1000 lines of the log file (adjust as needed)
    $lines = 1000;
    $file = file($logPath);
    $file = array_slice($file, -$lines);
    $logs = implode('', $file);

    // Highlight different log types
    $logs = preg_replace('/(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?ERROR.*?)(\n|$)/i', '<span style="color: red;">$1</span>$2', $logs);
    $logs = preg_replace('/(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?WARNING.*?)(\n|$)/i', '<span style="color: orange;">$1</span>$2', $logs);
    $logs = preg_replace('/(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?INFO.*?)(\n|$)/i', '<span style="color: blue;">$1</span>$2', $logs);
}

// Generate success message HTML if needed
$messageHtml = $message ? "<div style='background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;'>{$message}</div>" : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laravel Logs</title>
    <style>
        body {
            font-family: monospace;
            padding: 20px;
            background-color: #f5f5f5;
        }
        pre {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .controls {
            margin-bottom: 15px;
        }
        .btn {
            padding: 6px 12px;
            margin-right: 5px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background-color: #007bff;
            border: 1px solid #007bff;
            color: white;
        }
        .btn-danger {
            background-color: #dc3545;
            border: 1px solid #dc3545;
            color: white;
        }
        .file-info {
            margin-bottom: 10px;
            font-style: italic;
        }
        .confirm-dialog {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            z-index: 1000;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
    </style>
</head>
<body>
    <h1>Laravel Logs</h1>

    <?php echo $messageHtml; ?>

    <div class="controls">
        <button class="btn btn-primary" onclick="window.location.reload()">Refresh</button>
        <button class="btn btn-danger" onclick="showConfirmation()">Clear Logs</button>
    </div>

    <div class="file-info">
        Log file size: <?php echo $fileSize; ?> MB | Showing last <?php echo $lines ?? 0; ?> lines
    </div>

    <pre><?php echo $logs; ?></pre>

    <div class="overlay" id="overlay"></div>
    <div class="confirm-dialog" id="confirmDialog">
        <h3>Confirm Log Clearing</h3>
        <p>Are you sure you want to clear the log file? This cannot be undone.</p>
        <div>
            <form method="POST" action="">
                <input type="hidden" name="clear" value="1">
                <button type="submit" class="btn btn-danger">Yes, Clear Logs</button>
                <button type="button" class="btn" onclick="hideConfirmation()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function showConfirmation() {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('confirmDialog').style.display = 'block';
        }

        function hideConfirmation() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('confirmDialog').style.display = 'none';
        }
    </script>
</body>
</html>
