<?php
/**
 * Session Check Handler
 * Returns current user session data
 */

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'loggedIn' => true,
        'user' => [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'type' => $_SESSION['user_type']
        ]
    ]);
} else {
    echo json_encode([
        'loggedIn' => false
    ]);
}
