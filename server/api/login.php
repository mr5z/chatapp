<?php

require_once('api.php');

$email      = post('email');
$password   = post('password');

$sql = "SELECT `id`, `firstName`, `lastName`, `lastLogin`, `active` FROM users WHERE `email` = '$email' AND `password` = '$password' LIMIT 1";

$result = query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $user = $result->fetch_object();
        $response = buildResponse(STATUS_SUCCESS, $user);
        setUserAsActive($user->id);
    }
    else {
        $response = buildResponse(STATUS_ERROR, "No such user");
    }
}
else {
    $response = buildResponse(STATUS_ERROR, getDbError());
}

printResponse($response);