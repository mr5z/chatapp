<?php

require_once('api.php');

$recipientId = post('recipientId');
$recipientType = post('recipientType');

$sql = "SELECT u.firstName AS sender FROM users u
        INNER JOIN messages m
        ON m.senderId = u.id
        WHERE m.status = 'pending'
        AND m.recipientId = $recipientId
        AND m.recipientType = '$recipientType'
        GROUP BY u.id";

$result = query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $notifications = array();
        while($row = $result->fetch_object()) {
            $notifications[] = $row;
        }
        $response = buildResponse(STATUS_SUCCESS, $notifications);
    }
    else {
        $response = buildResponse(STATUS_ERROR, "All caught up!");
    }
}
else {
    $response = buildResponse(STATUS_ERROR, getDbError());
}

printResponse($response);

?>