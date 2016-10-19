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

function toTitleCase($text) {
    return ucwords(strtolower($text));
}

?>