<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true) ?: $_POST;

if (empty($data['email']) || empty($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Email and password required']);
    exit;
}

try {
    $conn = getDBConnection();

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($data['password'], $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_type'] = $user['user_type'];
    $_SESSION['user_name'] = $user['name'];

    $redirect = $user['user_type'] === 'company' ? 'company-home.html' : 'passenger-home.html';

    echo json_encode([
        'success' => true,
        'redirect' => $redirect,
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'type' => $user['user_type'],
            'balance' => $user['account_balance']
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Login failed']);
}
