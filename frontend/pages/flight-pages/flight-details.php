<?php
session_start();
include '../../includes/header.php';

// Get flight ID from URL parameter
$flightId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$flightId) {
    header('Location: search-flight.php');
    exit;
}

// Mock flight data for testing
$mockFlights = [
    1 => [
        'id' => 1,
        'flight_number' => 'SA101',
        'airline' => 'Sky Airlines',
        'departure_city' => 'Cairo',
        'arrival_city' => 'Dubai',
        'departure_time' => '08:00 AM',
        'arrival_time' => '12:30 PM',
        'duration' => '4h 30m',
        'flight_date' => '2025-12-25',
        'price' => 450,
        'available_seats' => 45,
        'aircraft_type' => 'Boeing 777'
    ],
    2 => [
        'id' => 2,
        'flight_number' => 'EM202',
        'airline' => 'Emirates',
        'departure_city' => 'Cairo',
        'arrival_city' => 'Dubai',
        'departure_time' => '02:00 PM',
        'arrival_time' => '06:30 PM',
        'duration' => '4h 30m',
        'flight_date' => '2025-12-25',
        'price' => 520,
        'available_seats' => 32,
        'aircraft_type' => 'Airbus A380'
    ],
    3 => [
        'id' => 3,
        'flight_number' => 'EA303',
        'airline' => 'EgyptAir',
        'departure_city' => 'Cairo',
        'arrival_city' => 'Dubai',
        'departure_time' => '10:00 PM',
        'arrival_time' => '02:30 AM',
        'duration' => '4h 30m',
        'flight_date' => '2025-12-25',
        'price' => 380,
        'available_seats' => 18,
        'aircraft_type' => 'Boeing 787'
    ]
];

// Fetch flight details from backend API or use mock data
$flightDetails = null;
$error = null;

try {
    $response = @file_get_contents('../../backend/flights/get-flight-details.php?id=' . $flightId);
    if ($response) {
        $flightDetails = json_decode($response, true);
    }
    
    // Fall back to mock data if no API response
    if (!$flightDetails && isset($mockFlights[$flightId])) {
        $flightDetails = $mockFlights[$flightId];
    }
} catch (Exception $e) {
    // Try mock data on error
    if (isset($mockFlights[$flightId])) {
        $flightDetails = $mockFlights[$flightId];
    } else {
        $error = "Failed to load flight details";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Details</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/flight-details.css">
</head>
<body>

<div class="flight-details-container">
    <h1 class="details-page-title">Flight Details</h1>

    <?php if ($error): ?>
        <div class="error-box">
            <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php elseif ($flightDetails): ?>
        <div class="flight-card-main">
            <!-- Flight Header -->
            <div class="flight-header">
                <div class="airline-name"><?php echo htmlspecialchars($flightDetails['airline'] ?? 'Unknown Airline'); ?></div>
                <div class="flight-route-main">
                    <?php echo htmlspecialchars($flightDetails['departure_city'] ?? 'N/A'); ?>
                    <span class="flight-arrow">→</span>
                    <?php echo htmlspecialchars($flightDetails['arrival_city'] ?? 'N/A'); ?>
                </div>
                <div class="flight-date-main">
                    <?php echo htmlspecialchars($flightDetails['flight_date'] ?? 'N/A'); ?>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="details-grid">
                <!-- Left Column -->
                <div class="detail-section">
                    <h2>Flight Information</h2>
                    <div class="detail-row">
                        <span class="detail-label">Flight Number:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($flightDetails['flight_number'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Aircraft:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($flightDetails['aircraft_type'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Departure City:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($flightDetails['departure_city'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Arrival City:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($flightDetails['arrival_city'] ?? 'N/A'); ?></span>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="detail-section">
                    <h2>Schedule</h2>
                    <div class="detail-row">
                        <span class="detail-label">Departure:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($flightDetails['departure_time'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Arrival:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($flightDetails['arrival_time'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Duration:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($flightDetails['duration'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($flightDetails['flight_date'] ?? 'N/A'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Pricing Section -->
            <div class="pricing-section">
                <h2>Pricing & Availability</h2>
                <div class="availability-badge">
                    ✓ <?php echo htmlspecialchars($flightDetails['available_seats'] ?? '0'); ?> seats available
                </div>
                <div class="price-display">
                    <span class="price-currency">$</span>
                    <span class="price-amount"><?php echo htmlspecialchars($flightDetails['price'] ?? '0'); ?></span>
                    <span class="price-per">per person</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-section">
                <a href="/Flight-Booking-Website-main/frontend/pages/flight-pages/seat-selection.php?flight_id=<?php echo $flightId; ?>" class="btn btn-primary btn-book">
                    Continue to Seat Selection
                </a>
                <a href="/Flight-Booking-Website-main/frontend/pages/flight-pages/search-flight.php" class="btn-back">
                    Back to Search
                </a>
            </div>
        </div>

    <?php else: ?>
        <div class="not-found-message">
            <h2>Flight Not Found</h2>
            <p>Sorry, we couldn't find the flight you're looking for.</p>
            <a href="search-flight.php" class="btn btn-primary" style="text-decoration: none; display: inline-block; margin-top: 16px;">
                Search Flights
            </a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>

<?php include '../../includes/footer.php'; ?>
