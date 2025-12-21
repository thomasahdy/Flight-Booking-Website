<?php
/**
 * User Login Handler
 * Processes login form submissions
 */


error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require_once __DIR__ . '/../config/database.php';


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}


$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if (strpos($contentType, 'application/json') !== false) {
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);


    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid JSON data',
            'error' => json_last_error_msg()
        ]);
        exit;
    }
} else {
    $data = $_POST;
}


if (isset($data['email'])) {
    $data['email'] = trim(strtolower($data['email']));
}

if (empty($data['email']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Email and password are required',
        'debug' => [
            'email_provided' => !empty($data['email']),
            'password_provided' => !empty($data['password']),
            'data_keys' => array_keys($data ?? [])
        ]
    ]);
    exit;
}

try {
    $conn = getDBConnection();


    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    $user = $stmt->fetch();

    if (!$user) {
        http_response_code(401);
        error_log("Login failed: User not found with email: " . $data['email']);
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        exit;
    }


    $passwordVerified = password_verify($data['password'], $user['password']);

    if (!$passwordVerified) {
        http_response_code(401);
        error_log("Login failed: Password verification failed for user ID: " . $user['id'] . ", email: " . $user['email']);
        error_log("Password hash length: " . strlen($user['password']));
        error_log("Input password length: " . strlen($data['password']));
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        exit;
    }


    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_type'] = $user['user_type'];
    $_SESSION['user_name'] = $user['full_name'];
    $_SESSION['user_email'] = $user['email'];


    $profileData = [];
    if ($user['user_type'] === 'passenger') {
        $stmt = $conn->prepare("SELECT * FROM passengers WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        $profileData = $stmt->fetch() ?: [];
    } else {
        $stmt = $conn->prepare("SELECT * FROM companies WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        $profileData = $stmt->fetch() ?: [];
    }


    $redirectUrl = $user['user_type'] === 'company'
        ? 'company-home.html'
        : 'passenger-home.html';

    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'redirect' => $redirectUrl,
        'user' => [
            'id' => $user['id'],
            'name' => $user['full_name'],
            'email' => $user['email'],
            'type' => $user['user_type'],
            'profile' => $profileData
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    error_log("Login error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Login failed. Please check your credentials and try again.',
        'error' => $e->getMessage()
    ]);
}
