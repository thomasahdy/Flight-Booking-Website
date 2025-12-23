<!-- Passenger flight history -->
<!-- 
Should contain:
authGuard
roleGuard (passenger)
Return:
Completed flights
Current flights
-->

<?php
require '../middleware/authGuard.php';
require '../middleware/roleGuard.php';
roleGuard('passenger');
require '../config/database.php';
header('Content-Type: application/json');

$passengerId = $_SESSION['user_id'];

try{
    $stmtCompleted = $db->prepare("
        SELECT f.flight_id, f.flight_name, f.fees, f.status AS flight_status, fp.status AS passenger_status
        FROM flights f
        JOIN flight_passengers fp ON f.flight_id = fp.flight_id
        WHERE fp.user_id = :passenger_id AND fp.status = 'completed'
        ORDER BY f.created_at DESC
"); 

$stmtCompleted->execute([':passenger_id' => $passengerId]);
$completedFlights = $stmtCompleted->fetchAll(PDO::FETCH_ASSOC);

$stmtCurrent = $db->prepare("
        SELECT f.flight_id, f.flight_name, f.fees, f.status AS flight_status, fp.status AS passenger_status
        FROM flights f
        JOIN flight_passengers fp ON f.flight_id = fp.flight_id
        WHERE fp.user_id = :passenger_id AND fp.status = 'registered'
        ORDER BY f.created_at ASC
"); 
$stmtCurrent->execute([':passenger_id' => $passengerId]);
$currentFlights = $stmtCurrent->fetchAll(PDO::FETCH_ASSOC);


echo json_encode(['success' => true, 'completed' => $completedFlights, 'current' => $currentFlights]);
}catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to fetch flights']);
    exit;
}