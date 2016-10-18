<?php

require_once("api.php");

$email = post("email");
$password = post("password");
$firstName = post("firstName");
$lastName = post("lastName");
$age = post("age");

if (empty($email) || 
	empty($password) || 
	empty($firstName) || 
	empty($lastName) || 
	empty($age)) {
	$response = buildResponse(STATUS_ERROR, "Some fields are empty");
}
else {
	$sql = "INSERT INTO
			users(email, password, firstName, lastName, age, dateRegistered)
			VALUES('$email', '$password', '$firstName', '$lastName', $age, NOW())";
			
	$result = query($sql);

	if ($result) {
		$response = buildResponse(STATUS_SUCCESS, array("id" => $db->insert_id));
	}
	else {
		$response = buildResponse(STATUS_ERROR, "Oops! Something went wrong. Please try again later");
	}
}

printResponse($response);

?>