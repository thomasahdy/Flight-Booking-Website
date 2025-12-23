<!-- Company approves a passenger -->
<!-- 
Should contain:
authGuard
roleGuard (company)
Verify flight ownership
Change booking status:
pending → registered
Notify passenger
-->
<?php
require '../middleware/authGuard.php';
require '../middleware/roleGuard.php';
roleGuard('company');
require '../config/database.php';
header('Content-Type: application/json');

$flightId = $_GET['flight_id'] ?? null;
$passengerId = $_GET['user_id'] ?? null;

if (empty($flightId) || !is_numeric($flightId) || empty($passengerId) || !is_numeric($passengerId)) {
    http_response_code(400);
    echo json_encode(['success' => false,    
        'error' => 'Invalid flight ID or passenger ID']);
    exit;
}

try{
    $stmtFlight = $db->prepare("SELECT * FROM flights WHERE flight_id = :flight_id AND company_id = :company_id");
    $stmtFlight->execute([':flight_id' => $flightId, ':company_id' => $_SESSION['user_id']]);
    if ($stmtFlight->rowCount() === 0) {
        http_response_code(400);
        echo json_encode(['success' => false,
            'error' => 'You do not own this flight']);
        exit;
    }

    $stmtBooking = $db->prepare("SELECT * FROM flight_passengers WHERE flight_id = :flight_id AND user_id = :user_id AND status = 'pending'");
    $stmtBooking->execute([':flight_id' => $flightId, ':user_id' => $passengerId]);
    if ($stmtBooking->rowCount() === 0) {
        http_response_code(400);
        echo json_encode(['success' => false,
            'error' => 'No pending booking found for this passenger on the specified flight']);
        exit;
    }  

    $stmtUser = $db->prepare("SELECT account_balance FROM passengers WHERE id = :user_id");
    $stmtUser->execute([':user_id' => $passengerId]);
    $passenger = $stmtUser->fetch(PDO::FETCH_ASSOC);
    if (!$passenger) {
        http_response_code(400);
        echo json_encode(['success' => false,
            'error' => 'Passenger not found']);
        exit;
    }   
    
    $db->beginTransaction();
    
    $stmtUpdateBooking = $db->prepare("UPDATE flight_passengers SET status = 'registered' WHERE flight_id = :flight_id AND user_id = :user_id");
    $stmtUpdateBooking->execute([':flight_id' => $flightId, ':user_id' => $passengerId]);
    $stmtUpdateFlight = $db->prepare("UPDATE flights SET registered_count = registered_count + 1 pending_count = pending_count - 1 WHERE flight_id = :flight_id");
    $stmtUpdateFlight->execute([':flight_id' => $flightId]);

    $db->commit();

    echo json_encode(['success' => true,
        'message' => 'Passenger approved successfully']);

}catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false,
        'error' => 'Database error: ' . $e->getMessage()]);
    exit;
}