<?php
session_start();
require_once "../config/database.php";

function register(){
    $db = getDBConnection();

    $name = $_POST['name'];
    $email = $_post['email'];
    $pasword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $tel = $_POST['tel'];
    $type = $_post['type'];

    $stmt = $db->prepare("INSERT INTO users(name, email, password, tel, type, balance) VALUES(?,?,?,?,?,0)");
    $stmt->execute([$name, $email, $password, $tel, $type]);

    $stmt -> execute([$name, $email, $password, $tel, $type]);

    $userID = $db->lastInsertId();

    if ($type === "company")
    {
        createCompanyProfile($userID);
    }


    if ($data['type'] === 'passenger') {
        $stmt = $conn->prepare("INSERT INTO passengers (user_id) VALUES (?)");
        $stmt->execute([$userId]);
    } else {
        $stmt = $conn->prepare("INSERT INTO companies (user_id, company_name) VALUES (?, ?)");
        $stmt->execute([$userId, $data['name']]);
    }

    $_SESSION['user_id'] = $userID;
    $_SESSION['role'] = $type;

    return ['success' => true, 'message' => 'Registration successful'];

}

function createCompanyProfile($userID)
{
    $db = getDBConnection();
    $db->prepare("INSERT INTO companies(user_id) VALUES(?)")->execute([$userID]);

}

function createPassengerprofile($userID)
{
    $db = getDBConnection();
    $db->prepare("INSERT INTO passengers(user_id) VALUES(?)")->execute([$userID]);

}
echo json_encode(register())