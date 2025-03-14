<?php
// Simple standalone log viewer
// Put this file in your public directory and access it at yourdomain.com/log-viewer.php

// Add some very basic security (change this password!)
$password = 'your-secret-password';

// Check if password is provided and correct
if (!isset($_POST['password']) || $_POST['password'] !== $password) {
    // Show login form
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Log Viewer - Login</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            .login-form { max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
            input[type="password"] { width: 100%; padding: 8px; margin: 10px 0; }
            button { padding: 8px 16px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        </style>
    </head>
    <body>
        <div class="login-form">
            <h2>Log Viewer Login</h2>
            <form method="post">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// If we get here, password is correct
$logPath = '/var/www/clients/client3/web65/web/storage/logs/laravel.log';

if (!file_exists($logPath)) {
    die('Log file not found: ' . $logPath);
}

// Get the last 1000 lines of the log file (adjust as needed)
$lines = 1000;
$file = file($logPath);
$file = array_slice($file, -$lines);
$logs = implode('', $file);

// Highlight different log types
$logs = preg_replace('/(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?ERROR.*?)(\n|$)/i', '<span style="color: red;">$1</span>$2', $logs);
$logs = preg_replace('/(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?WARNING.*?)(\n|$)/i', '<span style="color: orange;">$1</span>$2', $logs);
$logs = preg_replace('/(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?INFO.*?)(\n|$)/i', '<span style="color: blue;">$1</span>$2', $logs);
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
    </style>
</head>
<body>
    <h1>Laravel Logs</h1>
    <div class="controls">
        <button onclick="window.location.reload()">Refresh</button>
    </div>
    <pre><?php echo $logs; ?></pre>
</body>
</html>
