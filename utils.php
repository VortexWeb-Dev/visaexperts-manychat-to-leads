<?php
require_once(__DIR__ . '/crest/crest.php');

function getLanguageId($languageName)
{
    $languages = [
        ["ID" => "832", "VALUE" => "english"],
        ["ID" => "834", "VALUE" => "hindi"],
        ["ID" => "836", "VALUE" => "urdu"],
        ["ID" => "838", "VALUE" => "arabic"],
        ["ID" => "840", "VALUE" => "bengali"],
        ["ID" => "842", "VALUE" => "filipino"],
        ["ID" => "844", "VALUE" => "nepali"],
        ["ID" => "846", "VALUE" => "sinhala"]
    ];

    $languageName = strtolower($languageName);

    foreach ($languages as $language) {
        if ($language['VALUE'] === $languageName) {
            return $language['ID'];
        }
    }

    return null;
}

function getAgentForLanguage($languageId)
{
    $result = CRest::call('user.get', [
        'filter' => ['UF_USR_1731158853020' => $languageId]
    ]);

    $agents = $result['result'] ?? null;

    if ($agents) {
        do {
            $randomKey = array_rand($agents);
            $agent_id = $agents[$randomKey]['ID'] ?? null;
        } while ($agent_id == 288684 || $agent_id == 293638 || $agent_id == 133884);

        return $agent_id;
    }

    return null;
}
