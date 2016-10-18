<?php

require_once('database.php');
require_once('utils.php');

$db = new Database();
$db = $db->connect();

function query($sql) {
	global $db;
	return $db->query($sql);
}

function setUserAsActive($userId) {
	$sql = "UPDATE users SET lastLogin = NOW(), lastSeenActive = NOW(), active = TRUE WHERE id = $userId";
	$result = query($sql);
	if ($result) {
		// we don't care about the result
	}
	else {
		// something went wrong so ignore as well
	}
}

function updateUsersStatus() {
	$sql = "UPDATE users SET users.active = FALSE WHERE users.lastSeenActive <= NOW() - INTERVAL 3 SECOND";
	$result = query($sql);
	if ($result) {
		// we don't care about the result
	}
	else {
		// something went wrong so ignore as well
	}
}

function getUsersByRoomId($roomId) {
	$sql = "SELECT users.*
			FROM room_members
			INNER JOIN users
			ON users.id = room_members.userId
			INNER JOIN rooms
			ON rooms.id = room_members.roomId
			WHERE room_members.roomId = $roomId";
	return query($sql);
}

function getDbError() {
	global $db;
	return $db->error;
}

function getLastInsertId() {
	global $db;
	return $db->insert_id;
}

updateUsersStatus();

?>