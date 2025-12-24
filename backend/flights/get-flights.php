<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

try {
    $conn = getDBConnection();

    $where = ["status = 'active'"];
    $params = [];

    // Filter by origin/destination
    if (!empty($_GET['from'])) {
        $where[] = "itinerary LIKE ?";
        $params[] = '%' . $_GET['from'] . '%';
    }

    if (!empty($_GET['to'])) {
        $where[] = "itinerary LIKE ?";
        $params[] = '%' . $_GET['to'] . '%';
    }

    // Filter by company
    if (!empty($_GET['company_id'])) {
        $where[] = "f.company_id = ?";
        $params[] = $_GET['company_id'];
    }

    $sql = "SELECT f.*, u.name as company_name, u.tel as company_tel, u.email as company_email, c.logo_img
            FROM flights f
            JOIN users u ON f.company_id = u.id
            LEFT JOIN companies c ON u.id = c.user_id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY f.start_date ASC, f.start_time ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $flights = $stmt->fetchAll();

    // Add availability info
    foreach ($flights as &$flight) {
        $flight['available_seats'] = $flight['max_passengers'] - $flight['registered_passengers'] - $flight['pending_passengers'];
        $flight['is_full'] = $flight['available_seats'] <= 0;
    }

    echo json_encode([
        'success' => true,
        'flights' => $flights
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch flights']);
}
