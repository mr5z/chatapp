<?php

require_once('api.php');

$contactOwner   = post('contactOwner');
$contactId      = post('contactId');
$type           = post('type');

$sql = "INSERT INTO contacts(contactOwner, contactId, type, dateAdded)
        VALUES($contactOwner, $contactId, '$type', NOW())";
		
$result = query($sql);

if ($result) {
    $response = buildResponse(STATUS_SUCCESS);
}
else {
    $response = buildResponse(STATUS_ERROR, getDbError());
}

printResponse($response);