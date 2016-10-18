<?php

require_once('api.php');

$recipientId 	= post('recipientId');
$recipientType 	= post('recipientType');

switch ($recipientType) {
	case 'user':
		$sql = "SELECT * FROM `messages`
				WHERE `status` = 'pending'
				AND `recipientId` = $recipientId
				AND `recipientType` = '$recipientType'";
		break;
	case 'room':
		$sql = "SELECT messages.*
				FROM messages
				INNER JOIN room_messages
				ON room_messages.messageId = messages.id
				WHERE room_messages.status = 'pending'
				AND room_messages.roomId = $recipientId";
		break;
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
