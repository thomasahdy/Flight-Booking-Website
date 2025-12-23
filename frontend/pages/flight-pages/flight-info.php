<?php
session_start();
include '../../includes/header.php';

// Get flight ID from URL parameter
$flightId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$flightId) {
    header('Location: search-flight.php');
    exit;
}

$flightDetails = null;
$error = null;

// Fetch flight details
try {
    $response = file_get_contents('../../backend/flights/get-flight-details.php?id=' . $flightId);
    $flightDetails = json_decode($response, true);
} catch (Exception $e) {
    $error = "Failed to load flight information";
}
?>

<div style="max-width: 1440px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 2.5rem; margin-bottom: 24px;">Flight Information</h1>

    <?php if ($error): ?>
        <div style="background-color: #fee; padding: 16px; border-radius: 8px; color: #c00; margin-bottom: 24px;">
            <p><?php echo htmlspecialchars($error); ?></p>
        </div>
    <?php elseif ($flightDetails): ?>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <!-- Flight Overview -->
            <div style="background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 24px;">
                <h2 style="color: var(--purple-blue); margin-bottom: 16px;">Flight Overview</h2>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <div>
                        <p style="font-size: 1.2rem; font-weight: bold;">
                            <?php echo htmlspecialchars($flightDetails['departure_time'] ?? 'N/A'); ?>
                        </p>
                        <p style="color: #666; font-size: 0.9rem;">
                            <?php echo htmlspecialchars($flightDetails['departure_city'] ?? 'N/A'); ?>
                        </p>
                    </div>
                    <div style="text-align: center;">
                        <p style="color: #666; margin-bottom: 8px;">
                            <?php echo htmlspecialchars($flightDetails['duration'] ?? 'N/A'); ?>
                        </p>
                        <div style="border-top: 2px solid var(--purple-blue); width: 80px;"></div>
                    </div>
                    <div>
                        <p style="font-size: 1.2rem; font-weight: bold;">
                            <?php echo htmlspecialchars($flightDetails['arrival_time'] ?? 'N/A'); ?>
                        </p>
                        <p style="color: #666; font-size: 0.9rem;">
                            <?php echo htmlspecialchars($flightDetails['arrival_city'] ?? 'N/A'); ?>
                        </p>
                    </div>
                </div>
                <div style="background: #f9f9f9; padding: 16px; border-radius: 4px;">
                    <p><strong>Flight Number:</strong> <?php echo htmlspecialchars($flightDetails['flight_number'] ?? 'N/A'); ?></p>
                    <p><strong>Aircraft:</strong> <?php echo htmlspecialchars($flightDetails['aircraft_type'] ?? 'N/A'); ?></p>
                    <p><strong>Airline:</strong> <?php echo htmlspecialchars($flightDetails['airline'] ?? 'N/A'); ?></p>
                </div>
            </div>

            <!-- Pricing Information -->
            <div style="background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 24px;">
                <h2 style="color: var(--purple-blue); margin-bottom: 16px;">Pricing & Availability</h2>
                <div style="background: #f9f9f9; padding: 16px; border-radius: 4px; margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                        <span>Base Price:</span>
                        <span style="font-weight: bold;">$<?php echo htmlspecialchars($flightDetails['price'] ?? '0'); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                        <span>Taxes & Fees:</span>
                        <span style="font-weight: bold;">$<?php echo number_format((floatval($flightDetails['price'] ?? 0) * 0.1), 2); ?></span>
                    </div>
                    <div style="border-top: 1px solid #ddd; padding-top: 12px; display: flex; justify-content: space-between;">
                        <span style="font-weight: bold;">Total per person:</span>
                        <span style="font-size: 1.5rem; color: var(--purple-blue); font-weight: bold;">
                            $<?php echo number_format((floatval($flightDetails['price'] ?? 0) * 1.1), 2); ?>
                        </span>
                    </div>
                </div>
                <div style="background: #e8f5e9; padding: 16px; border-radius: 4px;">
                    <p style="color: #2e7d32; font-weight: bold; margin-bottom: 8px;">✓ Available</p>
                    <p style="color: #2e7d32; font-size: 0.9rem;">
                        <?php echo htmlspecialchars($flightDetails['available_seats'] ?? '0'); ?> seats available
                    </p>
                </div>
            </div>

            <!-- Flight Details -->
            <div style="background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 24px;">
                <h2 style="color: var(--purple-blue); margin-bottom: 16px;">Flight Details</h2>
                <div style="background: #f9f9f9; padding: 16px; border-radius: 4px;">
                    <p style="margin-bottom: 12px;"><strong>Date:</strong> <?php echo htmlspecialchars($flightDetails['flight_date'] ?? 'N/A'); ?></p>
                    <p style="margin-bottom: 12px;"><strong>Departure:</strong> <?php echo htmlspecialchars($flightDetails['departure_time'] ?? 'N/A'); ?></p>
                    <p style="margin-bottom: 12px;"><strong>Arrival:</strong> <?php echo htmlspecialchars($flightDetails['arrival_time'] ?? 'N/A'); ?></p>
                    <p style="margin-bottom: 12px;"><strong>Duration:</strong> <?php echo htmlspecialchars($flightDetails['duration'] ?? 'N/A'); ?></p>
                    <p style="margin-bottom: 12px;"><strong>Stops:</strong> <?php echo htmlspecialchars($flightDetails['stops'] ?? 'Non-stop'); ?></p>
                </div>
            </div>

            <!-- Amenities -->
            <div style="background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 24px;">
                <h2 style="color: var(--purple-blue); margin-bottom: 16px;">Amenities</h2>
                <div style="background: #f9f9f9; padding: 16px; border-radius: 4px;">
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 8px 0; border-bottom: 1px solid #ddd;">✓ Free Checked Baggage</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #ddd;">✓ In-flight Entertainment</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #ddd;">✓ Complimentary Meals</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #ddd;">✓ WiFi Access</li>
                        <li style="padding: 8px 0;">✓ Priority Boarding</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div style="margin-top: 32px; text-align: center;">
            <a href="seat-selection.php?flight_id=<?php echo $flightId; ?>" class="btn btn-primary" style="text-decoration: none; padding: 16px 48px; font-size: 1.1rem;">
                Select Seats & Book Now
            </a>
        </div>
    <?php else: ?>
        <div style="background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 24px; text-align: center;">
            <p style="color: #666;">Flight information not found.</p>
            <a href="search-flight.php" class="btn btn-primary" style="text-decoration: none; margin-top: 16px;">Back to Search</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>
