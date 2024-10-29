<?php
require_once(__DIR__ . '/crest/crest.php');

function logToFile($filename, $data)
{
    file_put_contents('logs/' . $filename, print_r($data, true), FILE_APPEND);
}

function getRequestData()
{
    $input = file_get_contents('php://input');
    return json_decode($input, true);
}

function handlePostRequest($data)
{
    if (!empty($data)) {
        logToFile('log.log', $data);


    } else {
        logToFile('err.log', 'Invalid or empty JSON');
        echo json_encode(['status' => 'error', 'message' => 'Invalid or empty JSON']);
        exit;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = getRequestData();
    handlePostRequest($data);
} else {
    header('Content-Type: application/json');
    header('HTTP/1.1 405 Method Not Allowed');
    $errorMessage = ['status' => 'error', 'message' => 'Only POST requests are allowed'];
    echo json_encode($errorMessage);
    logToFile('err.log', $errorMessage);
    exit;
}
