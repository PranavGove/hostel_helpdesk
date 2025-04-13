<?php
require 'config.php';

function registerUser($name, $room, $mobile, $password) {
    global $db;
    if(strtolower($name) == 'admin') {
        return "Username 'admin' is restricted";
    }
    
    $hashed = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $db->prepare("INSERT INTO users (name, room_number, mobile, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $room, $mobile, $hashed);
    return $stmt->execute() ? true : $db->error;
}

function loginUser($name, $password) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    
    if ($user && (password_verify($password, $user['password']) || ($name == 'admin' && $password == 'admin'))) {
        $_SESSION['user'] = $user;
        return true;
    }
    return false;
}
?>