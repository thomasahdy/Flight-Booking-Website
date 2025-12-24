<!-- Cancel a flight (critical) -->
<!-- 
Should contain:
authGuard
roleGuard (company)
Ownership check
Update flight status → cancelled
Refund passengers
Update booking status
Notify passengers
-->
<?php
require '../middleware/authGuard.php';
require '../middleware/roleGuard.php';
roleGuard('company');

require '../config/database.php';
header('Content-Type: application/json');

$flightId = $_GET['flight_id'] ?? null;

if (empty($flightId) || !is_numeric($flightId)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid flight ID']);
    exit;
}

try {
    $stmt = $db->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$company) {
        http_response_code(403);
        echo json_encode(['error' => 'Company not found']);
        exit;
    }

    $companyId = $company['id'];

    $stmt = $db->prepare("
        SELECT flight_id, fees, status 
        FROM flights 
        WHERE flight_id = ? AND company_id = ?
    ");
    $stmt->execute([$flightId, $companyId]);
    $flight = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$flight) {
        http_response_code(403);
        echo json_encode(['error' => 'Flight not found or unauthorized']);
        exit;
    }

    if ($flight['status'] === 'cancelled') {
        http_response_code(400);
        echo json_encode(['error' => 'Flight already cancelled']);
        exit;
    }

    $db->beginTransaction();

    $db->prepare("
        UPDATE flights 
        SET status = 'cancelled' 
        WHERE flight_id = ?
    ")->execute([$flightId]);

    $stmt = $db->prepare("
        SELECT user_id 
        FROM flight_passengers 
        WHERE flight_id = ? 
        AND status IN ('pending', 'registered')
    ");
    $stmt->execute([$flightId]);
    $passengers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalRefund = 0;

    foreach ($passengers as $p) {
        $db->prepare("
            UPDATE passengers 
            SET account_balance = account_balance + ? 
            WHERE user_id = ?
        ")->execute([$flight['fees'], $p['user_id']]);

        $db->prepare("
            UPDATE flight_passengers 
            SET status = 'completed' 
            WHERE flight_id = ? AND user_id = ?
        ")->execute([$flightId, $p['user_id']]);

        $totalRefund += $flight['fees'];
    }

    if ($totalRefund > 0) {
        $db->prepare("
            UPDATE companies 
            SET account_balance = account_balance - ? 
            WHERE id = ?
        ")->execute([$totalRefund, $companyId]);
    }

    $db->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Flight cancelled and passengers refunded',
        'refunded_passengers' => count($passengers)
    ]);

} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Cancellation failed']);
}
