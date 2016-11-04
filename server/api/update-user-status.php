<?php

require_once('api.php');

$userId     = post('userId');
$latitude   = post('latitude');
$longitude  = post('longitude');

$sql = "UPDATE users SET lastSeenActive = NOW() WHERE users.id = $userId";

$result = query($sql);

if ($result) {
    try {
        $friendlyAddress = reverseGeocode($latitude, $longitude);
        query("INSERT INTO locations(userId, 
                    latitude, 
                    longitude, 
                    friendlyAddress, 
                    dateGather)
               VALUES($userId,
                        $latitude, 
                        $longitude, 
                        '$friendlyAddress',
                        NOW(3))");
        $response = buildResponse(STATUS_SUCCESS);
    }
    catch(Exception $e) {
        $response = buildResponse(STATUS_ERROR, $e->getMessage());
    }
}
else {
    $response = buildResponse(STATUS_ERROR, getDbError());
}

function reverseGeocode($latitude, $longitude) {
    $json = getContents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude");
    $json = json_decode($json);
    return $json->status == "OK" ? $json->results[0]->formatted_address : "Unavailable";
}

function getContents($url) {
    if (!function_exists('curl_init')){ 
        exitWithResponse("CURL is not installed!");
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

printResponse($response);