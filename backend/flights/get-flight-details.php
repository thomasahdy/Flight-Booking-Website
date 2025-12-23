<!-- Detailed view of ONE flight -->
<!--
Should contain:
authGuard
Validate flight ID
Fetch:
Flight info
Itinerary
-->
<?php
require '../middleware/authGuard.php';
include '../config/database.php';

$flightId = $_GET['flight_id'] ?? null;

if (empty($flightId) || !is_numeric($flightId)) {
        http_response_code(400);
        echo json_encode(['success' => false,
            'error' => 'Invalid flight ID']);
        exit;
    }

try{
    if
    $query = "SELECT f.flight_id,
            f.flight_name,
            f.status,
            f.max_passengers,
            f.pending_count,
            f.registered_count,
            f.fees,
            f.completed
    FROM flights f
    WHERE f.flight_id = ?";
    
    $stmt = $db->prepare($query);
    $stmt->execute([$flightId]);
    $flight = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$flight) {
        http_response_code(404);
        echo json_encode(['success' => false,
            'error' => 'Flight not found']);
        exit;
    }
    $intineraryquery = "SELECT 
            city_order,
            city_name,
            arrival_time,
            departure_time
        FROM itineraries
        WHERE flight_id = ?
        ORDER BY city_order ASC";

    $intineraryStmt = $db->prepare($intineraryquery);
    $intineraryStmt->execute([$flightId]);
    $itinerary = $intineraryStmt->fetchAll(PDO::FETCH_ASSOC);

    $flight['intinerary'] = $itinerary;

    http_response_code(200);
    echo json_encode(['success' => true,
        'flight' => $flight,
        ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false,
        'error' => 'Failed to fetch flight details']);
}