<?php
session_start();
include '../../includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /Flight-Booking-Website-main/index.php');
    exit;
}

// Get flight ID from URL parameter
$flightId = isset($_GET['flight_id']) ? intval($_GET['flight_id']) : null;

if (!$flightId) {
    header('Location: search-flight.php');
    exit;
}

$flightDetails = null;
$seats = [];
$error = null;
$success = null;

// Fetch flight details
try {
    $response = file_get_contents('../../backend/flights/get-flight-details.php?id=' . $flightId);
    $flightDetails = json_decode($response, true);
} catch (Exception $e) {
    $error = "Failed to load flight details";
}

// Handle seat selection and booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedSeats = isset($_POST['seats']) ? $_POST['seats'] : [];
    
    if (empty($selectedSeats)) {
        $error = "Please select at least one seat";
    } else {
        try {
            $bookingData = [
                'user_id' => $_SESSION['user_id'],
                'flight_id' => $flightId,
                'seats' => $selectedSeats,
                'number_of_passengers' => count($selectedSeats)
            ];
            
            $ch = curl_init('../../backend/bookings/book-flight.php');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($bookingData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            $result = json_decode($response, true);
            
            if ($result['success'] ?? false) {
                $success = "Booking confirmed! Your flight has been booked.";
            } else {
                $error = $result['message'] ?? "Booking failed. Please try again.";
            }
        } catch (Exception $e) {
            $error = "Failed to process booking";
        }
    }
}

// Generate mock seat layout (6 rows x 6 seats)
$totalSeats = 36;
$seatLayout = [];
for ($i = 1; $i <= $totalSeats; $i++) {
    $seatLayout[] = [
        'number' => $i,
        'available' => rand(0, 1) === 1, // Mock availability
        'row' => ceil($i / 6),
        'column' => (($i - 1) % 6) + 1
    ];
}
?>

<div style="max-width: 1440px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 2.5rem; margin-bottom: 24px;">Select Your Seats</h1>

    <?php if ($success): ?>
        <div style="background-color: #e8f5e9; padding: 16px; border-radius: 8px; color: #2e7d32; margin-bottom: 24px;">
            <p><?php echo htmlspecialchars($success); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div style="background-color: #fee; padding: 16px; border-radius: 8px; color: #c00; margin-bottom: 24px;">
            <p><?php echo htmlspecialchars($error); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($flightDetails): ?>
        <div style="background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 24px; margin-bottom: 24px;">
            <h2 style="color: var(--purple-blue); margin-bottom: 16px;">Flight Information</h2>
            <p><strong><?php echo htmlspecialchars($flightDetails['departure_city'] ?? 'N/A'); ?></strong> → <strong><?php echo htmlspecialchars($flightDetails['arrival_city'] ?? 'N/A'); ?></strong></p>
            <p style="color: #666;">Date: <?php echo htmlspecialchars($flightDetails['flight_date'] ?? 'N/A'); ?> | Time: <?php echo htmlspecialchars($flightDetails['departure_time'] ?? 'N/A'); ?></p>
        </div>

        <form method="POST" style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
            <div style="background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 24px;">
                <h2 style="color: var(--purple-blue); margin-bottom: 16px;">Select Seats</h2>
                
                <div style="display: grid; grid-template-columns: repeat(6, 50px); gap: 8px; margin-bottom: 24px;">
                    <?php foreach ($seatLayout as $seat): ?>
                        <div>
                            <input 
                                type="checkbox" 
                                name="seats[]" 
                                value="<?php echo $seat['number']; ?>" 
                                id="seat_<?php echo $seat['number']; ?>"
                                <?php echo !$seat['available'] ? 'disabled' : ''; ?>
                                style="display: none;">
                            <label 
                                for="seat_<?php echo $seat['number']; ?>"
                                style="display: block; width: 100%; padding: 8px; text-align: center; border: 2px solid #ddd; border-radius: 4px; cursor: <?php echo $seat['available'] ? 'pointer' : 'not-allowed'; ?>; background: <?php echo !$seat['available'] ? '#f0f0f0' : '#fff'; ?>; font-weight: 600;">
                                <?php echo $seat['number']; ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div style="display: flex; gap: 16px; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 20px; height: 20px; border: 2px solid #4CAF50; background: #fff;"></div>
                        <span>Selected</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 20px; height: 20px; border: 2px solid #ddd; background: #fff;"></div>
                        <span>Available</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 20px; height: 20px; border: 2px solid #ddd; background: #f0f0f0;"></div>
                        <span>Unavailable</span>
                    </div>
                </div>
            </div>

            <div style="background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 24px; height: fit-content;">
                <h2 style="color: var(--purple-blue); margin-bottom: 16px;">Booking Summary</h2>
                <p><strong>Price per seat:</strong> $<?php echo htmlspecialchars($flightDetails['price'] ?? '0'); ?></p>
                <div id="summary" style="margin-bottom: 16px; padding: 16px; background: #f9f9f9; border-radius: 4px;">
                    <p>Selected seats: <span id="selected-seats">0</span></p>
                    <p style="font-size: 1.5rem; color: var(--purple-blue); font-weight: bold; margin-top: 8px;">
                        Total: $<span id="total-price">0</span>
                    </p>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Confirm Booking</button>
            </div>
        </form>

        <script>
            const pricePerSeat = <?php echo $flightDetails['price'] ?? 0; ?>;
            const checkboxes = document.querySelectorAll('input[name="seats[]"]');
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSummary);
                checkbox.addEventListener('change', function() {
                    const label = document.querySelector(`label[for="${this.id}"]`);
                    if (this.checked) {
                        label.style.background = '#e8f5e9';
                        label.style.borderColor = '#4CAF50';
                    } else {
                        label.style.background = '#fff';
                        label.style.borderColor = '#ddd';
                    }
                });
            });
            
            function updateSummary() {
                const selected = Array.from(checkboxes).filter(cb => cb.checked);
                const count = selected.length;
                const total = count * pricePerSeat;
                
                document.getElementById('selected-seats').textContent = count;
                document.getElementById('total-price').textContent = total.toFixed(2);
            }
        </script>
    <?php else: ?>
        <p>Flight not found.</p>
    <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>
