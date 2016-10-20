<?php

require_once('api.php');

$messagesId     = post('messagesId');
$userId         = post('userId');
$recipientId    = post('recipientId');
$recipientType  = post('recipientType');

switch ($recipientType) {
    case 'user':
        $sql = "UPDATE `messages`
                SET `status` = 'received',
                    `dateReceived` = NOW(3)
                WHERE id IN($messagesId)";
        break;
    case 'room':
        $sql = "UPDATE `room_messages`
                SET `status` = 'received'
                WHERE recipientId = $userId
                AND roomId = $recipientId
                AND messageId IN($messagesId)";
        break;
}

$result = query($sql);

if ($result) {
    if (getAffectedRows() > 0) {
        $response = buildResponse(STATUS_SUCCESS);
    }
    else {
        $response = buildResponse(STATUS_ERROR, "No rows affected");
    }
}
else {
    $response = buildResponse(STATUS_ERROR, getDbError());
}

printResponse($response);