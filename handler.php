<?php
require_once(__DIR__ . '/crest/crest.php');
require_once(__DIR__ . '/utils.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    
    file_put_contents('logs/log.log', print_r($data, true), FILE_APPEND);
    
    $stage = $_GET['stage'] ?? '';
    
    $name = $data['name'] ?? '';
    $phone = $data['whatsapp_phone'] ?? '';
    $dream_destination = $data['custom_fields']['dream_destination'] ?? '';
    $has_valid_passport = $data['custom_fields']['has_valid_passport'] ?? 0;
    $preferred_language = $data['custom_fields']['preferred_language'] ?? '';
    $chat_url = $data['live_chat_url'] ?? '';
    
    $status_id = ($stage === 'inactive') ? 5 : 4;

    $language_id = getLanguageId($preferred_language);
    $agent_id = getAgentForLanguage($language_id);

    $formData = [
        'TITLE' => $name . ' - ManyChat',
        'NAME' => $name,
        'PHONE' => [['VALUE' => $phone, 'VALUE_TYPE' => 'WORK']],
        'UF_CRM_LEAD_1728457253163' => $dream_destination,
        'SOURCE_ID' => '20|ANK_CHATS_APP24_WHATSAPP',
        'UF_CRM_1725276419822' => $has_valid_passport ? 'Y' : 'N',
        'UF_CRM_1730728557457' => $preferred_language,
        'UF_CRM_1730771131975' => $chat_url,
        'STATUS_ID' => $status_id,
        'ASSIGNED_BY_ID' => $agent_id
    ];

    $result = CRest::call('crm.lead.add', [
        'fields' => $formData,
    ]);

    file_put_contents('logs/res.log', print_r($result, true), FILE_APPEND);
    echo json_encode(['status' => 'success', 'result' => $result]);

} else {
    echo json_encode(['status' => 'error', 'message' => 'Not a valid method']);
}
