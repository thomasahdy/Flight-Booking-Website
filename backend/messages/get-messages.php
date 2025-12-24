<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $conn = getDBConnection();

    // Get conversations
    if (isset($_GET['with'])) {
        // Get messages with specific user
        $stmt = $conn->prepare("
            SELECT m.*, 
                   sender.name as sender_name, sender.user_type as sender_type,
                   receiver.name as receiver_name, receiver.user_type as receiver_type,
                   f.flight_name, f.flight_id
            FROM messages m
            JOIN users sender ON m.sender_id = sender.id
            JOIN users receiver ON m.receiver_id = receiver.id
            LEFT JOIN flights f ON m.flight_id = f.id
            WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)
            ORDER BY m.created_at ASC
        ");
        $stmt->execute([$_SESSION['user_id'], $_GET['with'], $_GET['with'], $_SESSION['user_id']]);
        $messages = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'messages' => $messages
        ]);
    } else {
        // Get all conversations
        $stmt = $conn->prepare("
            SELECT DISTINCT 
                   CASE 
                       WHEN m.sender_id = ? THEN m.receiver_id 
                       ELSE m.sender_id 
                   END as other_user_id,
                   u.name as other_user_name,
                   u.user_type as other_user_type,
                   (SELECT message FROM messages 
                    WHERE (sender_id = ? AND receiver_id = other_user_id) 
                       OR (sender_id = other_user_id AND receiver_id = ?)
                    ORDER BY created_at DESC LIMIT 1) as last_message,
                   (SELECT created_at FROM messages 
                    WHERE (sender_id = ? AND receiver_id = other_user_id) 
                       OR (sender_id = other_user_id AND receiver_id = ?)
                    ORDER BY created_at DESC LIMIT 1) as last_message_time
            FROM messages m
            JOIN users u ON (CASE WHEN m.sender_id = ? THEN m.receiver_id ELSE m.sender_id END) = u.id
            WHERE m.sender_id = ? OR m.receiver_id = ?
            ORDER BY last_message_time DESC
        ");
        $stmt->execute([
            $_SESSION['user_id'],
            $_SESSION['user_id'],
            $_SESSION['user_id'],
            $_SESSION['user_id'],
            $_SESSION['user_id'],
            $_SESSION['user_id'],
            $_SESSION['user_id'],
            $_SESSION['user_id']
        ]);
        $conversations = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'conversations' => $conversations
        ]);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch messages']);
}
