<?php
require_once(__DIR__ . '/crest/crest.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    
    file_put_contents('logs/add_log.log', print_r($data, true), FILE_APPEND);
    
    $name = $data['name'] ?? '';
    $phone = $data['whatsapp_phone'] ?? '';
    $preferred_language = $data['custom_fields']['preferred_language'] ?? '';
    $chat_url = $data['live_chat_url'] ?? '';

    $formData = [
        'TITLE' => $name . ' - ManyChat',
        'NAME' => $name,
        'PHONE' => [['VALUE' => $phone, 'VALUE_TYPE' => 'WORK']],
        'UF_CRM_1730728557457' => $preferred_language,
        'UF_CRM_1730771131975' => $chat_url,
        'SOURCE_ID' => '20|ANK_CHATS_APP24_WHATSAPP',
        'STATUS_ID' => "4",
    ];

    $result = CRest::call('crm.lead.add', [
        'fields' => $formData,
    ]);

    file_put_contents('logs/add_res.log', print_r($result, true), FILE_APPEND);
    echo json_encode(['status' => 'success', 'id' => $result['result']]);

} else {
    echo json_encode(['status' => 'error', 'message' => 'Not a valid method']);
}
