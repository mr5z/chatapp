<?php

require_once('api.php');

$senderId       = post('senderId');
$recipientId    = post('recipientId');
$recipientType  = post('recipientType');
$body           = post('body');

switch($recipientType) {
    case 'user':
        $response = createPrivateResponse($senderId, $recipientId, $body);
        break;
    case 'room':
        $response = createRoomResponse($senderId, $recipientId, $body);
        break;
    default:
        $response = createErrorResponse($recipientType);
        break;
}

printResponse($response);

function createMessage($senderId, $recipientId, $recipientType, $body) {
    $sql = "INSERT INTO messages(`senderId`, `recipientId`, `recipientType`, `body`, `status`)
            VALUES($senderId, $recipientId, '$recipientType', '$body', 'pending')";

    $result = query($sql);

    if ($result) {
        return buildResponse(STATUS_SUCCESS, getLastInsertId());
    }
    else {
        return buildResponse(STATUS_ERROR, getDbError());
    }
}

function createPrivateResponse($senderId, $recipientId, $body) {
    return createMessage($senderId, $recipientId, 'user', $body);
}

function createRoomResponse($senderId, $roomId, $body) {
    $response = createMessage($senderId, $roomId, 'room', $body);
    if ($response[RESPONSE_STATUS] == "success") {
        $users = getUsersByRoomId($roomId);
        if (!$users) {
            return buildResponse(STATUS_ERROR, getDbError());
        }
        if ($users->num_rows <= 0) {
            return buildResponse(STATUS_ERROR, "Noone hears you");
        }
        $messageId = $response[RESPONSE_MESSAGE];
        $values = "VALUES";
        while($row = $users->fetch_object()) {
            $values .= "($roomId, $row->id, $messageId),";
        }
        $values = substr($values, 0, strlen($values) - 1);
        $sql = "INSERT INTO room_messages(roomId, recipientId, messageId) $values";
        $result = query($sql);
        if ($result) {
            $response = buildResponse(STATUS_SUCCESS);
        }
        else {
            $response = buildResponse(STATUS_ERROR, getDbError());
        }
        return $response;
    }
    else {
        return buildResponse(STATUS_ERROR, $response[RESPONSE_STATUS]);
    }
}

function createErrorResponse($recipientType) {
    return buildResponse(STATUS_ERROR, "Unknown recipient type: $recipientType");
}