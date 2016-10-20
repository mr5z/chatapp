<?php

require_once('constants.php');

function get($key) {
    global $db;
    return isset($_GET[$key]) ? $db->real_escape_string($_GET[$key]) : "";
}

function post($key) {
    global $db;
    return isset($_POST[$key]) ? $db->real_escape_string($_POST[$key]) : "";
}

function getRequestMethod() {
    return $_SERVER['REQUEST_METHOD'];
}

function toTitleCase($text) {
    return ucwords(strtolower($text));
}

?>