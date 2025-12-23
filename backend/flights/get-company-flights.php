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

include '../config/database.php';

$companyId = $_SESSION['user_id'];

try{
    $query = "SELECT f.flight_id, f.flight_name, f.status, f.max_passengers, f.pending_count, f.registered_count
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