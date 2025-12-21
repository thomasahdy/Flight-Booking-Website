<?php
/**
 * User Registration Handler
 * Processes registration form submissions
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
    $data = json_decode(file_get_contents('php://input'), true);
} else {
    $data = $_POST;
}


if (isset($data['email'])) {
    $data['email'] = trim(strtolower($data['email']));
}

if (isset($data['name'])) {
    $data['name'] = trim($data['name']);
}
if (isset($data['tel'])) {
    $data['tel'] = trim($data['tel']);
}

$required = ['name', 'email', 'password', 'tel', 'type'];
$errors = [];

foreach ($required as $field) {
    if (empty($data[$field])) {
        $errors[] = ucfirst($field) . ' is required';
    }
}


if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}


if (!empty($data['password']) && strlen($data['password']) < 6) {
    $errors[] = 'Password must be at least 6 characters';
}


if (!empty($data['type']) && !in_array($data['type'], ['passenger', 'company'])) {
    $errors[] = 'Invalid account type';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

try {
    $conn = getDBConnection();


    $conn->beginTransaction();


    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);

    if ($stmt->fetch()) {
        $conn->rollBack();
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        exit;
    }


    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);


    $stmt = $conn->prepare("
        INSERT INTO users (email, password, full_name, phone, user_type) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $data['email'],
        $hashedPassword,
        $data['name'],
        $data['tel'],
        $data['type']
    ]);

    $userId = $conn->lastInsertId();


    if (!$userId || $userId == 0) {
        $conn->rollBack();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to create user account. Please try again.']);
        exit;
    }


    if ($data['type'] === 'passenger') {
        $stmt = $conn->prepare("INSERT INTO passengers (user_id) VALUES (?)");
        $stmt->execute([$userId]);
    } else {
        $stmt = $conn->prepare("INSERT INTO companies (user_id, company_name) VALUES (?, ?)");
        $stmt->execute([$userId, $data['name']]);
    }


    $conn->commit();


    $_SESSION['user_id'] = $userId;
    $_SESSION['user_type'] = $data['type'];
    $_SESSION['user_name'] = $data['name'];
    $_SESSION['user_email'] = $data['email'];


    $redirectUrl = $data['type'] === 'company'
        ? 'register-company.html'
        : 'register-passenger.html';

    echo json_encode([
        'success' => true,
        'message' => 'Registration successful',
        'redirect' => $redirectUrl,
        'user' => [
            'id' => $userId,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['tel'],
            'type' => $data['type']
        ]
    ]);

} catch (PDOException $e) {

    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    http_response_code(500);

    error_log("Registration error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Registration failed. Please check your database connection and try again.',
        'error' => $e->getMessage()
    ]);
}
