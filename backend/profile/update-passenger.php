<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'passenger') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true) ?: $_POST;

try {
    $conn = getDBConnection();

    // Update user table
    if (isset($data['name']) || isset($data['tel'])) {
        $updates = [];
        $params = [];

        if (isset($data['name'])) {
            $updates[] = "name = ?";
            $params[] = $data['name'];
        }
        if (isset($data['tel'])) {
            $updates[] = "tel = ?";
            $params[] = $data['tel'];
        }

        $params[] = $_SESSION['user_id'];

        $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Profile updated successfully'
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Update failed']);
}
