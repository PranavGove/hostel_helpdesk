<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Database connection
$db = new mysqli('localhost', 'root', '', 'hostel_helpdesk');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Input sanitization function
function sanitize($data) {
    global $db;
    return htmlspecialchars(strip_tags($db->real_escape_string(trim($data))));
}
?>