<?php

require_once('api.php');

$recipientId    = post('recipientId');
$recipientType  = post('recipientType');

$notifications = getNotificationsByRecipient($recipientId, $recipientType);

if ($notifications) {
    if ($notifications->num_rows > 0) {
        $result = array();
        while($row = $notifications->fetch_object()) {
            $result[] = $row;
        }
        $response = buildResponse(STATUS_SUCCESS, $result);
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