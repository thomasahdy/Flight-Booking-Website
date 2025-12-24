<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate required fields
$required = ['name', 'email', 'password', 'tel', 'type'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode(['success' => false, 'message' => ucfirst($field) . ' is required']);
        exit;
    }
}

if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email']);
    exit;
}

try {
    $conn = getDBConnection();

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        exit;
    }

    // Create user
    $stmt = $conn->prepare("INSERT INTO users (email, password, name, tel, user_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['email'],
        password_hash($data['password'], PASSWORD_DEFAULT),
        $data['name'],
        $data['tel'],
        $data['type']
    ]);

    $userId = $conn->lastInsertId();

    // Create profile
    if ($data['type'] === 'passenger') {
        $stmt = $conn->prepare("INSERT INTO passengers (user_id) VALUES (?)");
        $stmt->execute([$userId]);
    } else {
        $stmt = $conn->prepare("INSERT INTO companies (user_id) VALUES (?)");
        $stmt->execute([$userId]);
    }

    // Set session
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_type'] = $data['type'];
    $_SESSION['user_name'] = $data['name'];

    $redirect = $data['type'] === 'company' ? 'register-company.html' : 'register-passenger.html';

    echo json_encode([
        'success' => true,
        'redirect' => $redirect,
        'user' => [
            'id' => $userId,
            'name' => $data['name'],
            'email' => $data['email'],
            'type' => $data['type']
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Registration failed']);
}
