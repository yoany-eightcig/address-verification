<?php
header("Access-Control-Allow-Origin: *");

/*
$bypassAddress = [
    "750 tristram",
    "21719 Crystal Way",
    "25125 Orange Lane",
];
*/

$bypassProvince = [
    "Puerto Rico",
];

$bypassCountry = [
    "KR",   
];

function bypassAddress($field, $data) {
    $result = false;

    foreach ($data as $value) {
        if (strtolower($_GET[$field]) == strtolower($value)) {
            $result = true;
        }
    }
    return $result;
}

if (count($_GET)) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.shipengine.com/v1/addresses/validate');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array($_GET)));
    curl_setopt($ch, CURLOPT_POST, 1);

    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Api-Key: ElJkhJuQIRoFq/kDEblco4LpZqRCdYNIoAVG7SywSXw';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close ($ch);

    $json = json_decode($result);

    $data = [];
    foreach ($json as $value) {
        $data = $value;
    }

    $csv = array_map('str_getcsv', file('bypass.csv'));
    $bypassAddress = [];
    foreach ($csv as $value) {
        $bypassAddress[] = $value[1];       
    }

    if (bypassAddress('country_code', $bypassCountry) || bypassAddress('state_province', $bypassProvince) || bypassAddress('address_line1', $bypassAddress) ) {
        echo '{"status":"verified","original_address":{"name":"Anthony Roddy","phone":"(801) 722-9455","company_name":"","address_line1":"1779 W 220 S","address_line2":"","address_line3":null,"city_locality":"Provo","state_province":"Utah","postal_code":"84601","country_code":"US","address_residential_indicator":"unknown"},"matched_address":{"name":"ANTHONY RODDY","phone":"(801) 722-9455","company_name":"","address_line1":"1779 W 220 S","address_line2":"","address_line3":null,"city_locality":"PROVO","state_province":"UT","postal_code":"84601-3839","country_code":"US","address_residential_indicator":"yes"},"messages":[]}';
    } else {
        echo json_encode($data); 
    }
    
}

