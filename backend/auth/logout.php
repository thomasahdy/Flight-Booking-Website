<?php
/**
 * User Logout Handler
 * Destroys session and logs user out
 */

require_once __DIR__ . '/../config/database.php';


session_unset();
session_destroy();


if (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
} else {

    header('Location: ../../frontend/login.html');
}
exit;
