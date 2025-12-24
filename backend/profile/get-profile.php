<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $conn = getDBConnection();

    // Get user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    $profile = [];

    // Get profile data based on user type
    if ($user['user_type'] === 'passenger') {
        $stmt = $conn->prepare("SELECT * FROM passengers WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $profile = $stmt->fetch() ?: [];
    } else {
        $stmt = $conn->prepare("SELECT * FROM companies WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $profile = $stmt->fetch() ?: [];
    }

    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'tel' => $user['tel'],
            'type' => $user['user_type'],
            'balance' => $user['account_balance'],
            'profile' => $profile
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch profile']);
}
