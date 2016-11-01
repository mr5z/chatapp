<?php

require_once('api.php');

$userId     = post('userId');
$latitude   = post('latitude');
$longitude  = post('longitude');

$sql = "UPDATE users SET lastSeenActive = NOW() WHERE users.id = $userId";

$result = query($sql);

if ($result) {
    query("INSERT INTO locations(userId, latitude, longitude, dateGather) VALUES($userId, $latitude, $longitude, NOW(3))");
    $response = buildResponse(STATUS_SUCCESS);
}
else {
    $response = buildResponse(STATUS_ERROR, getDbError());
}

printResponse($response);