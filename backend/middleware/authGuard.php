<!-- Protect endpoints from anonymous access  -->

<!--
Should contain:
Check if session exists
Block request if not logged in
Return unauthorized response
Used by:
ALL protected backend files
-->
<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access. Please log in.']);
    exit;
}

?>