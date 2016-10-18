<?php

require_once('api.php');

$messagesId 	= post('messagesId');
$recipientId 	= post('recipientId');
$recipientType 	= post('recipientType');

switch ($recipientType) {
	case 'user':
		$sql = "UPDATE `messages` SET `status` = 'received', `dateReceived` = NOW(3) WHERE id IN($messagesId)";
		break;
	case 'room':
		$sql = "UPDATE `room_messages` SET `status` = 'received' WHERE recipientId = $recipientId";
		break;
}

$result = query($sql);

if ($result) {
	$response = buildResponse(STATUS_SUCCESS);
}
else {
	$response = buildResponse(STATUS_ERROR, getDbError());
}

printResponse($response);