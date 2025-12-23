<?php
session_start();
require_once "../config/database.php";

function login(){
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($_POST['password'], $user['password']))
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['type'];
        return ['success' => true, "role" => $user['type'], 'message' => 'Login successful'];

    }
    return ["success" => false];
}
echo json_encode(login());