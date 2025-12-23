<!-- Passenger books a flight -->
<!-- 
Should contain:
authGuard
roleGuard (passenger)
Validate:
Flight active
Seats available
Passenger not already booked
Create booking (pending)
-->
<?php
require '../middleware/authGuard.php';
require '../middleware/roleGuard.php';
roleGuard('passenger');

require '../config/database.php';
header('Content-Type: application/json');
$userId = $_SESSION['user_id'];

$flightId = $_GET['flight_id'] ?? null;
if (empty($flightId) || !is_numeric($flightId)) {
    http_response_code(400);
    echo json_encode(['success' => false,
        'error' => 'Invalid flight ID']);
    exit;
}

try{
    $query = $db->prepare("SELECT * FROM flights WHERE flight_id = :flight_id AND status = 'active' AND completed = FALSE AND registered_count < max_passengers");
    $query->execute([':flight_id' => $flightId]);
    if ($query->rowCount() === 0) {
        http_response_code(400);
        echo json_encode(['success' => false,
        'error' => 'Flight is not available for booking']);
        exit;
    }
    $flight = $query->fetch(PDO::FETCH_ASSOC);

    $query = $db->prepare("SELECT * FROM flight_passengers WHERE flight_id = :flight_id AND user_id = :user_id");
    $query->execute([':flight_id' => $flightId, ':user_id' => $userId]);
    if ($query->rowCount() > 0) {
        http_response_code(400);
        echo json_encode(['success' => false,
        'error' => 'You have already booked this flight']);
        exit;
    }

    $query = $db->prepare("INSERT INTO flight_passengers (flight_id, user_id, status) VALUES (:flight_id, :user_id, 'pending')");
    $query->execute([':flight_id' => $flightId, ':user_id' => $userId]);

    $query = $db->prepare("UPDATE flights SET pending_count = pending_count + 1 WHERE flight_id = :flight_id");
    $query->execute([':flight_id' => $flightId]);

    echo json_encode(['success' => true,
        'message' => 'Flight booking is pending']);
}catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false,
        'error' => 'Database error: ' . $e->getMessage()]);
    exit;
}