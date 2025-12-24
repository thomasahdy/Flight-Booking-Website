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

if (empty($data['flight_id'])) {
    echo json_encode(['success' => false, 'message' => 'Flight ID required']);
    exit;
}

try {
    $conn = getDBConnection();
    $conn->beginTransaction();

    // Verify ownership
    $stmt = $conn->prepare("SELECT * FROM flights WHERE id = ? AND company_id = ?");
    $stmt->execute([$data['flight_id'], $_SESSION['user_id']]);
    $flight = $stmt->fetch();

    if (!$flight) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Flight not found or unauthorized']);
        exit;
    }

    // Get all bookings for this flight
    $stmt = $conn->prepare("SELECT passenger_id, amount FROM bookings WHERE flight_id = ? AND status IN ('confirmed', 'pending')");
    $stmt->execute([$data['flight_id']]);
    $bookings = $stmt->fetchAll();

    // Refund all passengers
    foreach ($bookings as $booking) {
        $stmt = $conn->prepare("UPDATE users SET account_balance = account_balance + ? WHERE id = ?");
        $stmt->execute([$booking['amount'], $booking['passenger_id']]);
    }

    // Add refund amount to company (deduct)
    $totalRefund = array_sum(array_column($bookings, 'amount'));
    $stmt = $conn->prepare("UPDATE users SET account_balance = account_balance - ? WHERE id = ?");
    $stmt->execute([$totalRefund, $_SESSION['user_id']]);

    // Cancel all bookings
    $stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE flight_id = ?");
    $stmt->execute([$data['flight_id']]);

    // Cancel flight
    $stmt = $conn->prepare("UPDATE flights SET status = 'cancelled' WHERE id = ?");
    $stmt->execute([$data['flight_id']]);

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Flight cancelled and passengers refunded',
        'refunded_amount' => $totalRefund,
        'passengers_refunded' => count($bookings)
    ]);

} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Failed to cancel flight']);
}
