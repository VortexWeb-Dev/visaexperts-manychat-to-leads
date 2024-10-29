<?php
$logDir = __DIR__ . '/logs/';

if (!is_dir($logDir)) {
    echo json_encode(['status' => 'error', 'message' => 'Logs directory does not exist.']);
    exit;
}

$files = array_diff(scandir($logDir), ['.', '..']);

foreach ($files as $file) {
    $filePath = $logDir . $file;

    if (is_file($filePath)) {
        if (!unlink($filePath)) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete file: ' . $file]);
            exit;
        }
    }
}

echo json_encode(['status' => 'success', 'message' => 'All log files cleared successfully.']);
?>
