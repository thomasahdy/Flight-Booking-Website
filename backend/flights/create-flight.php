<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'company') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate required fields
$required = ['flight_name', 'flight_id', 'itinerary', 'fees', 'max_passengers', 'start_date', 'start_time', 'end_date', 'end_time'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
        exit;
    }
}

try {
    $conn = getDBConnection();

    // Check if flight_id already exists
    $stmt = $conn->prepare("SELECT id FROM flights WHERE flight_id = ?");
    $stmt->execute([$data['flight_id']]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Flight ID already exists']);
        exit;
    }

    // Create flight
    $stmt = $conn->prepare("
        INSERT INTO flights (company_id, flight_name, flight_id, itinerary, fees, max_passengers, start_date, start_time, end_date, end_time)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $_SESSION['user_id'],
        $data['flight_name'],
        $data['flight_id'],
        $data['itinerary'], // JSON string of cities
        $data['fees'],
        $data['max_passengers'],
        $data['start_date'],
        $data['start_time'],
        $data['end_date'],
        $data['end_time']
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Flight created successfully',
        'flight_id' => $conn->lastInsertId()
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to create flight']);
}
