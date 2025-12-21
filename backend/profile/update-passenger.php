<?php
/**
 * Update Passenger Profile Handler
 * Processes passenger profile completion form
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require_once __DIR__ . '/../config/database.php';

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get POST data
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if (strpos($contentType, 'application/json') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);
} else {
    $data = $_POST;
}

// Check if user is logged in (session or user_id in request)
$userId = null;

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} elseif (isset($data['user_id'])) {
    // Allow user_id from request body as fallback
    $userId = $data['user_id'];
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated. Please login first.']);
    exit;
}

try {
    $conn = getDBConnection();

    // Verify user is a passenger
    $stmt = $conn->prepare("SELECT user_type FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user || $user['user_type'] !== 'passenger') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Access denied. Only passengers can update passenger profiles.']);
        exit;
    }

    // Update passenger profile
    $stmt = $conn->prepare("
        UPDATE passengers 
        SET profile_image = ?,
            date_of_birth = ?,
            nationality = ?,
            passport_number = ?,
            emergency_contact_name = ?,
            emergency_contact_phone = ?
        WHERE user_id = ?
    ");

    $stmt->execute([
        $data['profileImage'] ?? null,
        $data['dateOfBirth'] ?? null,
        $data['nationality'] ?? null,
        $data['passport'] ?? null,
        $data['emergencyContact'] ?? null,
        $data['emergencyPhone'] ?? null,
        $userId
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Profile updated successfully',
        'redirect' => 'passenger-home.html'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    error_log("Profile update error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to update profile. Please try again.',
        'error' => $e->getMessage()
    ]);
}
