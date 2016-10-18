<?php

require_once('api.php');

$userId = post('userId');

$sql = "UPDATE users SET lastSeenActive = NOW() WHERE users.id = $userId";

$result = query($sql);

if ($result) {
	$response = buildResponse(STATUS_SUCCESS);
}
else {
	$response = buildResponse(STATUS_ERROR, getDbError());
}

printResponse($response);