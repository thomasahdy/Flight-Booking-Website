<!-- List company flights -->

<!-- 
Should contain:
authGuard
roleGuard (company)
Fetch flights owned by company
Count:
pending passengers
registered passengers
Used in:
Company Home page
-->
<?php
require '../middleware/authGuard.php';
require '../middleware/roleGuard.php';
roleGuard('company');

require '../config/database.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$stmt = $db->prepare("SELECT id FROM companies WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$companyId = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$companyId) {
    http_response_code(403);
    echo json_encode(['error' => 'Company not found for the user']);
    exit;
}
$companyId = $companyId['id'];

try{
    $query = "SELECT f.flight_id,
            f.flight_name,
            f.status,
            f.max_passengers,
            f.pending_count,
            f.registered_count,
            f.fees,
            f.completed
    FROM flights f
    WHERE f.company_id = ?";
    
    $stmt = $db->prepare($query);
    $stmt->execute([$companyId]);
    $flights = $stmt->fetchAll(PDO::FETCH_ASSOC);http_response_code(200);
    echo json_encode(['success' => true,
        'flights' => $flights]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false,
        'error' => 'Failed to fetch flights']);
}