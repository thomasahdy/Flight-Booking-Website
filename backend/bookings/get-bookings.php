<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'passenger') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $conn = getDBConnection();

    $filter = isset($_GET['status']) ? $_GET['status'] : '';

    $sql = "SELECT b.*, f.flight_name, f.flight_id as flight_code, f.itinerary, f.start_date, f.start_time, f.end_date, f.end_time, f.status as flight_status,
                   u.name as company_name
            FROM bookings b
            JOIN flights f ON b.flight_id = f.id
            JOIN users u ON f.company_id = u.id
            WHERE b.passenger_id = ?";

    $params = [$_SESSION['user_id']];

    if ($filter) {
        $sql .= " AND b.status = ?";
        $params[] = $filter;
    }

    $sql .= " ORDER BY b.booking_date DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $bookings = $stmt->fetchAll();

    // Separate into categories
    $completed = [];
    $current = [];

    foreach ($bookings as $booking) {
        if ($booking['status'] === 'completed' || $booking['flight_status'] === 'completed') {
            $completed[] = $booking;
        } else if ($booking['status'] !== 'cancelled' && $booking['flight_status'] === 'active') {
            $current[] = $booking;
        }
    }

    echo json_encode([
        'success' => true,
        'bookings' => $bookings,
        'completed' => $completed,
        'current' => $current
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch bookings']);
}
