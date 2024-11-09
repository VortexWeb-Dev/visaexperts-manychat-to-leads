<?php
require_once(__DIR__ . '/crest/crest.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    file_put_contents('logs/update_log.log', print_r($data, true), FILE_APPEND);

    $id = $data['custom_fields']['lead_id'] ?? '';
    $dream_destination = $data['custom_fields']['dream_destination'] ?? '';
    $has_valid_passport = $data['custom_fields']['has_valid_passport'] ?? 0;

    // $chat_url = $data['live_chat_url'] ?? '';

    $formData = [
        'UF_CRM_LEAD_1728457253163' => $dream_destination,
        'UF_CRM_1725276419822' => $has_valid_passport ? 'Y' : 'N',
        // 'UF_CRM_1730771131975' => $chat_url,
        'STATUS_ID' => 'UC_NJRLRN',
    ];

    if (!empty($id)) {

        $result = CRest::call('crm.lead.update', [
            'id' => $id,
            'fields' => $formData,
        ]);

        file_put_contents('logs/update_res.log', print_r($result, true), FILE_APPEND);
        echo json_encode(['status' => 'success', 'result' => $result]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Not a valid method']);
}
