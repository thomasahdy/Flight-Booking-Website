<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if (empty($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Flight ID required']);
    exit;
}

try {
    $conn = getDBConnection();

    // Get flight details
    $stmt = $conn->prepare("
        SELECT f.*, u.name as company_name, u.tel as company_tel, u.email as company_email, 
               u.id as company_user_id, c.logo_img, c.bio, c.address
        FROM flights f
        JOIN users u ON f.company_id = u.id
        LEFT JOIN companies c ON u.id = c.user_id
        WHERE f.id = ?
    ");
    $stmt->execute([$_GET['id']]);
    $flight = $stmt->fetch();

    if (!$flight) {
        echo json_encode(['success' => false, 'message' => 'Flight not found']);
        exit;
    }

    // Get registered passengers
    $stmt = $conn->prepare("
        SELECT u.name, u.email, u.tel, b.booking_date, b.payment_type, b.status
        FROM bookings b
        JOIN users u ON b.passenger_id = u.id
        WHERE b.flight_id = ? AND b.status IN ('confirmed', 'completed')
        ORDER BY b.booking_date ASC
    ");
    $stmt->execute([$_GET['id']]);
    $flight['registered_passengers'] = $stmt->fetchAll();

    // Get pending passengers
    $stmt = $conn->prepare("
        SELECT u.name, u.email, u.tel, b.booking_date, b.payment_type
        FROM bookings b
        JOIN users u ON b.passenger_id = u.id
        WHERE b.flight_id = ? AND b.status = 'pending'
        ORDER BY b.booking_date ASC
    ");
    $stmt->execute([$_GET['id']]);
    $flight['pending_passengers_list'] = $stmt->fetchAll();

    $flight['available_seats'] = $flight['max_passengers'] - count($flight['registered_passengers']) - count($flight['pending_passengers_list']);

    echo json_encode([
        'success' => true,
        'flight' => $flight
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch flight details']);
}
