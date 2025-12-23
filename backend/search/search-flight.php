<!-- Search available flights -->
<!-- 
Should contain:
Receive from & to cities
Find matching itinerary
Filter:
active flights
Return flight list
-->
<?php
require '../config/database.php';
header('Content-Type: application/json');  

$fromCity = $_GET['from_city'] ?? null;
$toCity = $_GET['to_city'] ?? null;

if (empty($fromCity) || empty($toCity)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' =>
    'Missing from_city or to_city']);
    exit;
}

try{
    $query= "SELECT f.flight_id, f.flight_name, f.fees, f.max_passengers, f.registered_count, f.pending_count, f.completed, f.status, i1.city_name AS from_city, i1.departure_time AS departure_time, i2.city_name AS to_city, i2.arrival_time AS arrival_time
    FROM flights f
    JOIN itineraries i1 ON f.flight_id = i1.flight_id
    JOIN itineraries i2 ON f.flight_id = i2.flight_id
    WHERE i1.city_name = ? AND i2.city_name = ? AND i1.city_order < i2.city_order AND f.status = 'active' AND f.completed = FALSE AND f.registered_count < f.max_passengers";

    $stmt = $db->prepare($query);
    $stmt->execute([$fromCity, $toCity]);
    $flights = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$flights) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' =>
        'No available flights found']);
        exit;
    }
    http_response_code(200);
    echo json_encode(['success' => true, 'flights' =>
    $flights]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    exit;
}