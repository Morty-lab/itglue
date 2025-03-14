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
        .error {
            color: red;
        }
        .info {
            color: blue;
        }
        .warning {
            color: orange;
        }
    </style>
</head>
<body>
    <h1>Laravel Logs</h1>
    <button onclick="window.location.reload()">Refresh</button>
    <hr>
    <pre><?php
// Highlight different log types
$logs = $logs ?? '';
$logs = preg_replace('/(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?ERROR.*?)(\n|$)/i', '<span class="error">$1</span>$2', $logs);
$logs = preg_replace('/(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?WARNING.*?)(\n|$)/i', '<span class="warning">$1</span>$2', $logs);
$logs = preg_replace('/(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?INFO.*?)(\n|$)/i', '<span class="info">$1</span>$2', $logs);
echo $logs;
?></pre>
</body>
</html>
