<!-- Retrieve conversation -->
<!-- 
Should contain:
authGuard
Fetch messages between users
Optional filter by flight
-->
<?php
require '../middleware/authGuard.php';
require '../config/database.php';
header('Content-Type: application/json');

$userId = $_SESSION['user_id'];
$otherUserId = $_GET['other_user_id'] ?? null;
$flightId = $_GET['flight_id'] ?? null;

try{
    $query = "
        SELECT id, sender_id, receiver_id, message
        FROM messages
        WHERE sender_id = :user_id OR receiver_id = :user_id
    ";
    $params = [':user_id' => $userId];

    if ($otherUserId) {
        $query .= " AND (sender_id = :other_user_id OR receiver_id = :other_user_id)";
        $params[':other_user_id'] = $otherUserId;
    }

    $query .= " ORDER BY sent_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'messages' => $messages]);
}catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to fetch messages']);
    exit;
}