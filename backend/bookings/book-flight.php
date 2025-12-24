<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'passenger') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true) ?: $_POST;

if (empty($data['flight_id']) || empty($data['payment_type'])) {
    echo json_encode(['success' => false, 'message' => 'Flight ID and payment type required']);
    exit;
}

try {
    $conn = getDBConnection();
    $conn->beginTransaction();

    // Get flight details
    $stmt = $conn->prepare("SELECT * FROM flights WHERE id = ? AND status = 'active'");
    $stmt->execute([$data['flight_id']]);
    $flight = $stmt->fetch();

    if (!$flight) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Flight not found or not available']);
        exit;
    }

    // Check availability
    $available = $flight['max_passengers'] - $flight['registered_passengers'] - $flight['pending_passengers'];
    if ($available <= 0) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Flight is full']);
        exit;
    }

    // Check if already booked
    $stmt = $conn->prepare("SELECT id FROM bookings WHERE passenger_id = ? AND flight_id = ? AND status != 'cancelled'");
    $stmt->execute([$_SESSION['user_id'], $data['flight_id']]);
    if ($stmt->fetch()) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Already booked this flight']);
        exit;
    }

    $status = 'pending';

    // Process payment if using account
    if ($data['payment_type'] === 'account') {
        $stmt = $conn->prepare("SELECT account_balance FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if ($user['account_balance'] < $flight['fees']) {
            $conn->rollBack();
            echo json_encode(['success' => false, 'message' => 'Insufficient balance']);
            exit;
        }

        // Deduct from passenger
        $stmt = $conn->prepare("UPDATE users SET account_balance = account_balance - ? WHERE id = ?");
        $stmt->execute([$flight['fees'], $_SESSION['user_id']]);

        // Add to company
        $stmt = $conn->prepare("UPDATE users SET account_balance = account_balance + ? WHERE id = ?");
        $stmt->execute([$flight['fees'], $flight['company_id']]);

        $status = 'confirmed';
    }

    // Create booking
    $stmt = $conn->prepare("
        INSERT INTO bookings (passenger_id, flight_id, status, payment_type, amount)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$_SESSION['user_id'], $data['flight_id'], $status, $data['payment_type'], $flight['fees']]);

    // Update flight passenger count
    if ($status === 'confirmed') {
        $stmt = $conn->prepare("UPDATE flights SET registered_passengers = registered_passengers + 1 WHERE id = ?");
    } else {
        $stmt = $conn->prepare("UPDATE flights SET pending_passengers = pending_passengers + 1 WHERE id = ?");
    }
    $stmt->execute([$data['flight_id']]);

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Flight booked successfully',
        'booking_id' => $conn->lastInsertId(),
        'status' => $status
    ]);

} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Booking failed']);
}
