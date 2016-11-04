<?php

require_once('api.php');

$name           = post('name');
$password       = post('password');
$accessibility  = post('accessibility');
$description    = post('description');
$roomMembers    = post('roomMembers');
$ownerId        = post('ownerId');

$sql = "INSERT INTO rooms(name, password, accessibility, description, ownerId)
        VALUES('$name', '$password', '$accessibility', '$description', $ownerId)";

$result = query($sql);

if ($result) {
    $roomMembers = explode(',', $roomMembers);
    $roomId = getLastInsertId();
    $values = "VALUES";
    foreach($roomMembers as $userId) {
        $values .= "($roomId, $userId, NOW()),";
    }
    $values = substr($values, 0, strlen($values) - 1);
    $sql = "INSERT INTO room_members(roomId, userId, dateJoined) $values";
    $result = query($sql);
    if ($result) {
        $response = buildResponse(STATUS_SUCCESS);
    }
    else {
        $response = buildResponse(STATUS_ERROR, getDbError());
    }
}
else {
    $response = buildResponse(STATUS_ERROR, getDbError());
}

printResponse($response);