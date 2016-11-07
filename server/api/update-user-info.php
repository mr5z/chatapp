<?php

require_once('api.php');

$userId     = post('userId');
$firstName  = post('firstName');
$lastName   = post('lastName');
$city       = post('city');

$sql = "UPDATE users SET firstName = '$firstName', lastName = '$lastName', city = '$city' WHERE id = $userId";
$result = query($sql);

if ($result) {
    $response = buildResponse(STATUS_SUCCESS);
}
else {
    $response = buildResponse(STATUS_ERROR, getDbError());
}

printResponse($response);

?>