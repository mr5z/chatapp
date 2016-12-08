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
            WHERE users.lastSeenActive <= NOW() - INTERVAL 7 SECOND";
    $result = query($sql);
    if ($result) {
        // we don't care about the result
    }
    else {
        // something went wrong so ignore as well
    }
}

function getUserById($userId) {
    $sql = "SELECT users.*, 
                COALESCE((SELECT locations.friendlyAddress
                          FROM locations
                          WHERE locations.userId = users.id
                          ORDER BY locations.dateGather
                          DESC LIMIT 1), 'Unavailable') lastKnownLocation,
                COALESCE((SELECT CONCAT(locations.latitude, ',', locations.longitude)
                          FROM locations
                          WHERE locations.userId = users.id
                          ORDER BY locations.dateGather
                          DESC LIMIT 1), '') position
            FROM users
            WHERE users.id = $userId";
    return query($sql);
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

function getRoomsByUserId($userId) {
    $sql = "SELECT
                rooms.id,
                rooms.name,
                t1.activeUsers,
                t1.totalUsers
            FROM room_members 
            INNER JOIN rooms ON room_members.roomId = rooms.id
            INNER JOIN users ON room_members.userId = users.id
            INNER JOIN (  
                SELECT roomId, SUM(users.active) AS activeUsers, COUNT(users.id) AS totalUsers
                FROM users 
                INNER JOIN room_members ON users.id = room_members.userId
                GROUP BY roomId
            ) t1 ON t1.roomID = rooms.id
            WHERE room_members.userId = $userId
            ORDER BY rooms.name ASC";
    return query($sql);
}

function getContactListByUserId($userId) {
    $sql = "SELECT users.* FROM contacts
            INNER JOIN users
            ON contacts.contactId = users.id
            WHERE contacts.contactOwner = $userId";
    return query($sql);
}

function uploadFile($file) {
    $validTypes = array("png", "jpg", "jpeg", "gif");
    $targetDirectory = "../files/";
    $maxSize = 25 * 1024 * 1024; // Megabytes

    // Upload cover page
    if (!isset($file) || empty($file["name"])) {
        return buildResponse(STATUS_ERROR, "Cannot find file in form: " . $file["error"]);
    }
        
    $targetFile = $targetDirectory . basename($file["name"]);
    $fileExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    
    if (empty($fileExtension)) {
        $fileExtension = mimeToExt($file['type']);
    }
    // $valid = getimagesize($file["tmp_name"]);

    // Validation
    // if(!$valid) {
        // return buildResponse(STATUS_ERROR, "File is not an image!");
    // }

    // Check file size
    if ($file["size"] > $maxSize) {
        return buildResponse(STATUS_ERROR, "File is too large.");
    }

    // Disallow certain file formats
    // if(!in_array($fileExtension, $validTypes)) {
        // return buildResponse(STATUS_ERROR, "Only jpg, jpeg, png & gif files are allowed");
    // }

    // Try to upload file
    $newFilename = round(microtime(true) * 1000) . ".$fileExtension";
    if (move_uploaded_file($file["tmp_name"], $targetDirectory.$newFilename)) {
        return buildResponse(STATUS_SUCCESS, "/files/$newFilename");
    }
    else {
        return buildResponse(STATUS_ERROR, "An error occurred while uploading the file");
    }
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