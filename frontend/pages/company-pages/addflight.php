<?php require_once('../../includes/header.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Flight</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/addflight.css">
</head>

<body>
<main>
<form class="form-container" onsubmit="addFlight(event)">

    <h1 class="form-header">Add New Flight</h1>

    <div class="form-group">
        <label>Flight Name</label>
        <input type="text" id="flightName" required>
    </div>

    <div class="form-group">
        <label>Flight ID</label>
        <input type="text" id="flightId" required>
    </div>

    <div class="form-group">
        <label>Itinerary (cities separated by -)</label>
        <input type="text" id="itinerary" placeholder=" " required>
    </div>

    <div class="form-group">
        <label>Fees ($)</label>
        <input type="number" id="fees" min="0" required>
    </div>

    <div class="form-group">
        <label>Number of Passengers</label>
        <input type="number" id="passengers" min="1" required>
    </div>

    <div class="form-group">
        <label>Start Time</label>
        <input type="datetime-local" id="startTime" required>
    </div>

    <div class="form-group">
        <label>End Time</label>
        <input type="datetime-local" id="endTime" required>
    </div>

    <div class="form-action-btn-container">
        <button type="submit" class="form-action-btn">Add Flight</button>
    </div>

</form>
</main>

<script src="../../js/add-flight.js"></script>
</body>
</html>

<?php require_once('../../includes/footer.php'); ?>
