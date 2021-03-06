<?php

require_once('api.php');

$userId         = post('userId');
$recipientId 	= post('recipientId');
$recipientType 	= post('recipientType');

switch ($recipientType) {
    case 'user':
        $sql = "SELECT
                    messages.id,
                    messages.senderId,
                    messages.recipientId,
                    DATE_FORMAT(messages.dateSent, '%Y-%m-%dT%TZ') dateSent,
                    messages.type,
                    messages.body
                FROM messages
                WHERE status = 'pending'
                AND recipientId = $recipientId
                AND recipientType = '$recipientType'";
        break;
    case 'room':
        $sql = "SELECT
                    messages.id,
                    messages.senderId,
                    messages.recipientId,
                    DATE_FORMAT(messages.dateSent, '%Y-%m-%dT%TZ') dateSent,
                    messages.type,
                    messages.body
                FROM messages
                LEFT JOIN room_messages
                ON room_messages.messageId = messages.id
                WHERE room_messages.status = 'pending'
                AND room_messages.roomId = $recipientId
                AND room_messages.recipientId = $userId";
        break;
    default: exitWithResponse("Invalid recipient type");
}
		
$result = query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $messages = array();
        while($row = $result->fetch_object()) {
            $messages[] = $row;
        }
        $response = buildResponse(STATUS_SUCCESS, $messages);
    }
    else {
        $response = buildResponse(STATUS_ERROR);
    }
}
else {
    $response = buildResponse(STATUS_ERROR, getDbError());
}

printResponse($response);
