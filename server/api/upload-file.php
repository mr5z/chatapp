<?php

require_once('api.php');

$messages = array();
$filePath = array();
foreach($_FILES as $file) {
    $result = uploadFile($file);
    if ($result[RESPONSE_STATUS] != "success") {
        $messages[] = basename($file["name"] . ": " . $result[RESPONSE_MESSAGE]);
        continue;
    }
    else {
        $filePath[] = "http://" . $_SERVER['HTTP_HOST'] . $result[RESPONSE_MESSAGE];
    }
}

if (count($_FILES) > 0) {
    if (!$messages) {
        $response = buildResponse(STATUS_SUCCESS, $filePath);
    }
    else {
        $response = buildResponse(STATUS_ERROR, "Failed to upload the following files: " . implode(', ', $messages));
    }
}
else {
    $response = buildResponse(STATUS_ERROR, "No file(s) found");
}

printResponse($response);

?>