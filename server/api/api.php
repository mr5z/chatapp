<?php

require_once('database.php');
require_once('utils.php');

if (getRequestMethod() != 'POST') {
    exitWithResponse("Request method not allowed");
}

$db = (new Database())->connect();

// Update user status every time this is called
updateUsersStatus();

function query($sql) {
    global $db;
    return $db->query($sql);
}

function setUserAsActive($userId) {
    $sql = "UPDATE users
            SET lastLogin = NOW(),
                lastSeenActive = NOW(),
                active = TRUE
            WHERE id = $userId";
    $result = query($sql);
    if ($result) {
        // we don't care about the result
    }
    else {
        // something went wrong so ignore as well
    }
}

function updateUsersStatus() {
    $sql = "UPDATE users
            SET users.active = FALSE
            WHERE users.lastSeenActive <= NOW() - INTERVAL 3 SECOND";
    $result = query($sql);
    if ($result) {
        // we don't care about the result
    }
    else {
        // something went wrong so ignore as well
    }
}

function getUsersByRoomId($roomId) {
    $sql = "SELECT users.*
            FROM room_members
            INNER JOIN users
            ON users.id = room_members.userId
            INNER JOIN rooms
            ON rooms.id = room_members.roomId
            WHERE room_members.roomId = $roomId";
    return query($sql);
}

function getNotificationsByRecipient($recipientId, $recipientType) {
    $sql = "SELECT u.firstName AS sender, u.id AS senderId FROM users u
            INNER JOIN messages m
            ON m.senderId = u.id
            WHERE m.status = 'pending'
            AND m.recipientId = $recipientId
            AND m.recipientType = '$recipientType'
            GROUP BY u.id";
    return query($sql);
}

function getDbError() {
    global $db;
    return $db->error;
}

function getLastInsertId() {
    global $db;
    return $db->insert_id;
}

function getAffectedRows() {
    global $db;
    return $db->affected_rows;
}

function buildResponse($status, $message = "") {
    return array(RESPONSE_STATUS => $status, 
                 RESPONSE_MESSAGE => $message);
}

function printResponse($response) {
    header("Content-Type: application/json; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    print json_encode($response);
}

function exitWithResponse($message = "") {
    $response = buildResponse(STATUS_ERROR, $message);
    printResponse($response);
    exit();
}

?>