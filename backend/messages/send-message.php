<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true) ?: $_POST;

if (empty($data['receiver_id']) || empty($data['message'])) {
    echo json_encode(['success' => false, 'message' => 'Receiver and message required']);
    exit;
}

try {
    $conn = getDBConnection();

    $stmt = $conn->prepare("
        INSERT INTO messages (sender_id, receiver_id, flight_id, message)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $_SESSION['user_id'],
        $data['receiver_id'],
        $data['flight_id'] ?? null,
        $data['message']
    ]);

    echo json_encode([
        'success' => true,
        'message_id' => $conn->lastInsertId()
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to send message']);
}
