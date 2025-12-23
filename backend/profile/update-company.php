<?php
/**
 * Update Company Profile Handler
 * Processes company profile completion form
 */


error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require_once __DIR__ . '/../config/database.php';


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if (strpos($contentType, 'application/json') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);
} else {
    $data = $_POST;
}


$userId = null;

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} elseif (isset($data['user_id'])) {

    $userId = $data['user_id'];
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated. Please login first.']);
    exit;
}


if (empty($data['companyName']) || empty($data['address']) || empty($data['license'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Company name, address, and license number are required']);
    exit;
}

try {
    $conn = getDBConnection();


    $stmt = $conn->prepare("SELECT user_type FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user || $user['user_type'] !== 'company') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Access denied. Only companies can update company profiles.']);
        exit;
    }


    $stmt = $conn->prepare("
        UPDATE companies 
        SET company_name = ?,
            logo_url = ?,
            bio = ?,
            address = ?,
            license_number = ?,
            tax_id = ?
        WHERE user_id = ?
    ");

    $stmt->execute([
        trim($data['companyName']),
        $data['logo'] ?? null,
        $data['bio'] ?? null,
        trim($data['address']),
        trim($data['license']),
        $data['taxId'] ?? null,
        $userId
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Company profile updated successfully',
        'redirect' => 'company-home.html'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    error_log("Company profile update error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to update company profile. Please try again.',
        'error' => $e->getMessage()
    ]);
}
