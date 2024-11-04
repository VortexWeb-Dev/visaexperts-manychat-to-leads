<?php
require_once(__DIR__ . '/crest/crest.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    
    file_put_contents('logs/log.log', print_r($data, true), FILE_APPEND);
    
    $name = $data['name'] ?? '';
    $phone = $data['whatsapp_phone'] ?? '';
    $preferred_country = $data['last_input_text'] ?? '';

    $formData = [
        'TITLE' => $name . ' - ManyChat',
        'NAME' => $name,
        'PHONE' => [['VALUE' => $phone, 'VALUE_TYPE' => 'WORK']],
        'UF_CRM_LEAD_1679811828816' => ($preferred_country == 'Poland') ? 602 : (($preferred_country == 'Czech Republic') ? 604 : ''),
        'SOURCE_ID' => '20|ANK_CHATS_APP24_WHATSAPP',
    ];

    $result = CRest::call('crm.lead.add', [
        'fields' => $formData,
    ]);

    file_put_contents('logs/res.log', print_r($result, true), FILE_APPEND);
    echo json_encode(['status' => 'success', 'result' => $result]);

} else {
    echo json_encode(['status' => 'error', 'message' => 'Not a valid method']);
}

