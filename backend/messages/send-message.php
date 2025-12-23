<!-- Send a message -->
<!-- 
Should contain:
authGuard
Validate sender/receiver
Save message
-->

<?php
require '../middleware/authGuard.php';
require '../config/database.php';
header('Content-Type: application/json');

$userId = $_SESSION['user_id'];
$receiverId = $_POST['receiver_id'] ?? null;
$message = $_POST['message'] ?? null;

if (!$receiverId || !$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

try {
    $stmt = $db->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$receiverId]);
    $receive = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$receive) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Receiver not found']);
        exit;
    }  
    $stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $receiverId, $message]);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to send message']);
}