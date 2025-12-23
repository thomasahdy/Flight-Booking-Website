<!-- 
Create a new flight
Should contain:
authGuard
roleGuard (company)
Validate flight data
Insert flight record
Insert itinerary (multiple cities)
Set flight status = active
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

$flightName = trim($data['flight_name']) ?? '';
$fees = floatval($data['fees']) ?? -1;
$maxPassengers = intval($data['max_passengers']) ?? 0;
$itinerary = $data['itinerary'] ?? [];

if (empty($flightName) || $fees < 0 || $maxPassengers <= 0 || !is_array($itinerary) || count($itinerary) < 2) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid flight data']);
    exit;
}

foreach ($itinerary as $city) {
    if (empty(trim($city['city_name'])) ||  empty(trim($city['arrival_day'])) || empty(trim($city['arrival_time'])) || empty(trim($city['departure_day'])) || empty(trim($city['departure_time']))) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid itinerary data']);
        exit;
    }
}

try {
    $db->beginTransaction();

    $stmt = $db->prepare("INSERT INTO flights (company_id, flight_name, fees, max_passengers) VALUES (?, ?, ?, ?)");
    $stmt->execute([$companyId, $flightName, $fees, $maxPassengers]);

    $flightID = $db->lastInsertId();
    $stmtItinerary = $db->prepare("INSERT INTO itineraries (flight_id, city_order, city_name, arrival_time, departure_time) VALUES (?, ?, ?, ?, ?)");

    foreach ($itinerary as $index => $city) {
        $cityName = trim($city['city_name']);
        $arrivalTime = trim($city['arrival_time']);
        $arrivalDateTime = $city['arrival_day'] . ' ' . $city['arrival_time'];
        $departureDateTime = $city['departure_day'] . ' ' . $city['departure_time'];
        $stmtItinerary->execute([$flightID, $index + 1, $cityName, $arrivalDateTime, $departureDateTime]);
    }

    $db->commit();
    http_response_code(201);
    echo json_encode(['message' => 'Flight created successfully', 'flight_id' => $flightID]);
} catch (Exception $e) {
    $db->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Failed to create flight', 'details' => $e->getMessage()]);
}
?>