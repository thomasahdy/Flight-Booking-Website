<?php
session_start();
include '../../includes/header.php';

// Get search parameters from GET request
$departureCity = isset($_GET['from']) ? htmlspecialchars($_GET['from']) : '';
$arrivalCity = isset($_GET['to']) ? htmlspecialchars($_GET['to']) : '';
$departureDate = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '';
$passengers = isset($_GET['passengers']) ? intval($_GET['passengers']) : 1;

$flights = [];
$error = null;
$searched = false;

// Mock flights data for demonstration
$mockFlights = [
    [
        'id' => 1,
        'airline' => 'Sky Airlines',
        'departure_city' => 'Cairo',
        'arrival_city' => 'Dubai',
        'departure_time' => '08:00 AM',
        'arrival_time' => '12:30 PM',
        'price' => 450,
        'available_seats' => 45,
        'flight_date' => '2025-12-25'
    ],
    [
        'id' => 2,
        'airline' => 'Emirates',
        'departure_city' => 'Cairo',
        'arrival_city' => 'Dubai',
        'departure_time' => '02:00 PM',
        'arrival_time' => '06:30 PM',
        'price' => 520,
        'available_seats' => 32,
        'flight_date' => '2025-12-25'
    ],
    [
        'id' => 3,
        'airline' => 'EgyptAir',
        'departure_city' => 'Cairo',
        'arrival_city' => 'Dubai',
        'departure_time' => '10:00 PM',
        'arrival_time' => '02:30 AM',
        'price' => 380,
        'available_seats' => 18,
        'flight_date' => '2025-12-25'
    ]
];

// Fetch flights based on search criteria
if ($departureCity && $arrivalCity && $departureDate) {
    $searched = true;
    try {
        // Try to fetch from backend API
        $queryParams = http_build_query([
            'from' => $departureCity,
            'to' => $arrivalCity,
            'date' => $departureDate,
            'passengers' => $passengers
        ]);
        $response = @file_get_contents('../../backend/search/search-flight.php?' . $queryParams);
        if ($response) {
            $flights = json_decode($response, true) ?? [];
        }
        
        // If no API response, use mock data for matching routes
        if (empty($flights)) {
            foreach ($mockFlights as $flight) {
                if (strtolower($flight['departure_city']) === strtolower($departureCity) && 
                    strtolower($flight['arrival_city']) === strtolower($arrivalCity)) {
                    $flights[] = $flight;
                }
            }
        }
    } catch (Exception $e) {
        $error = "Failed to search flights";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Flights</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/search-flight.css">
</head>
<body>

<main class="search-container">
    <h1 class="page-title">Search Flights</h1>

    <!-- Search Form -->
    <div class="search-form-card">
        <form method="GET">
            <div class="search-grid">
                <div class="form-group">
                    <label for="from">From</label>
                    <input type="text" id="from" name="from" value="<?php echo $departureCity; ?>" placeholder="Departure City" required>
                </div>
                <div class="form-group">
                    <label for="to">To</label>
                    <input type="text" id="to" name="to" value="<?php echo $arrivalCity; ?>" placeholder="Arrival City" required>
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" value="<?php echo $departureDate; ?>" required>
                </div>
                <div class="form-group">
                    <label for="passengers">Passengers</label>
                    <select id="passengers" name="passengers">
                        <?php for ($i = 1; $i <= 9; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $passengers === $i ? 'selected' : ''; ?>>
                                <?php echo $i; ?> <?php echo $i === 1 ? 'Passenger' : 'Passengers'; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">Search Flights</button>
        </form>
    </div>

    <!-- Error Message -->
    <?php if ($error): ?>
        <div class="error-message">
            <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- Flights Results -->
    <div>
        <?php if ($searched && count($flights) > 0): ?>
            <h2 class="results-header">
                Found <?php echo count($flights); ?> flight<?php echo count($flights) !== 1 ? 's' : ''; ?>
            </h2>
            <?php foreach ($flights as $flight): ?>
                <div class="flight-card">
                    <div class="flight-info">
                        <h3><?php echo htmlspecialchars($flight['airline'] ?? 'Unknown Airline'); ?></h3>
                        <div class="flight-route">
                            <?php echo htmlspecialchars($flight['departure_city'] ?? 'N/A'); ?> 
                            <span style="color: var(--purple-blue);">→</span> 
                            <?php echo htmlspecialchars($flight['arrival_city'] ?? 'N/A'); ?>
                        </div>
                        <div class="flight-details">
                            <p><strong>Departure:</strong> <?php echo htmlspecialchars($flight['departure_time'] ?? 'N/A'); ?></p>
                            <p><strong>Arrival:</strong> <?php echo htmlspecialchars($flight['arrival_time'] ?? 'N/A'); ?></p>
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($flight['flight_date'] ?? 'N/A'); ?></p>
                        </div>
                    </div>
                    <div class="flight-pricing">
                        <div class="price">$<?php echo htmlspecialchars($flight['price'] ?? '0'); ?></div>
                        <div class="seats-available">
                            <?php echo htmlspecialchars($flight['available_seats'] ?? '0'); ?> seats available
                        </div>
                        <a href="flight-details.php?id=<?php echo htmlspecialchars($flight['id'] ?? ''); ?>" 
                           class="btn btn-primary" 
                           style="text-decoration: none; display: block; text-align: center; padding: 10px;">
                            View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php elseif ($searched && count($flights) === 0): ?>
            <div class="empty-state">
                <h3>No Flights Found</h3>
                <p>Sorry, we couldn't find any flights matching your search criteria.</p>
                <p style="margin-top: 16px; color: #999;">Try adjusting your search parameters and try again.</p>
            </div>

        <?php else: ?>
            <div class="empty-state">
                <h3>Start Your Search</h3>
                <p>Enter your flight details above and click "Search Flights" to find available options.</p>
            </div>

        <?php endif; ?>
    </div>
</main>

</body>
</html>

<?php include '../../includes/footer.php'; ?>
