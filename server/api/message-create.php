<?php

require_once('api.php');

$senderId       = post('senderId');
$recipientId    = post('recipientId');
$recipientType  = post('recipientType');
$type           = post('type');
$body           = post('body');

switch($recipientType) {
    case 'user':
        $response = createPrivateResponse($senderId, $recipientId, $type, $body);
        break;
    case 'room':
        $response = createRoomResponse($senderId, $recipientId, $type, $body);
        break;
    default:
        $response = createErrorResponse($recipientType);
        break;
}

printResponse($response);

function createMessage($senderId, $recipientId, $recipientType, $type, $body) {
    $sql = "INSERT INTO messages(`senderId`, `recipientId`, `recipientType`, `type`, `body`, `status`)
            VALUES($senderId, $recipientId, '$recipientType', '$type', '$body', 'pending')";

    $result = query($sql);

    if ($result) {
        return buildResponse(STATUS_SUCCESS, getLastInsertId());
    }
    else {
        return buildResponse(STATUS_ERROR, getDbError());
    }
}

function createPrivateResponse($senderId, $recipientId, $type, $body) {
    return createMessage($senderId, $recipientId, 'user', $type, $body);
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
            $userId = $row->id;
            if ($userId != $senderId) {
                $values .= "($roomId, $userId, $messageId),";
            }
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