<!-- Enforce role-based access -->

<!-- 
Should contain:
Function that checks:
required role
current user role
Block if roles don’t match
Examples:
Only company → add-flight
Only passenger → book-flight
-->
<?php
function roleGuard($requiredRole) {

    if(session_status() === PHP_SESSION_NONE)
    {
        session_start();
    }
    
    // Check if user role is set in session
    if (!isset($_SESSION['user_type'])) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, "error" => "Access denied. No role assigned."]);
        exit();
    }

    // compare roles
    $currentUserRole = $_SESSION['user_type'];

    if ($currentUserRole !== $requiredRole) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, "error" => "Access denied. Insufficient permissions."]);
        exit();
    }
}
?>