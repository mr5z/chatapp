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

function buildResponse($status, $message = "") {
    return array(RESPONSE_STATUS => $status, 
                 RESPONSE_MESSAGE => $message);
}

function printResponse($response) {
    header("Content-Type: application/json; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    print json_encode($response);
}

function toTitleCase($text) {
    return ucwords(strtolower($text));
}

?>