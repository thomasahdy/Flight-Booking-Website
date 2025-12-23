<?php
require_once('../../includes/header.php');


$company = [
    'name' => 'Sky Airlines',
    'bio' => 'International airline company providing high quality flights.',
    'address' => 'Cairo, Egypt',
    'logo' => '../assets/logo.png'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Profile</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/companyprofile.css">
</head>
<body>

<main class="company-profile">

    
    <div class="profile-header">
        <img src="<?= $company['logo'] ?>" class="profile-logo" alt="Company Logo">
        <h1 class="profile-name"><?= $company['name'] ?></h1>
    </div>

    <!-- ===== Info ===== -->
    <div class="profile-info">
        <div class="info-item">
            <label>Bio</label>
            <p><?= $company['bio'] ?></p>
        </div>

        <div class="info-item">
            <label>Address</label>
            <p><?= $company['address'] ?></p>
        </div>
    </div>

    <!-- ===== Actions ===== -->
    <div class="profile-actions">
        <a href="edit-company-profile.php">
            <button>Edit Profile</button>
        </a>
    </div>

    <!-- ===== Flights List ===== -->
    <section class="profile-flights">
        <h2>Flights</h2>

        <table class="flights-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Itinerary</th>
                </tr>
            </thead>
            <tbody>
                <tr onclick="window.location='flight-details.php?id=201'">
                    <td>201</td>
                    <td>Cairo → Dubai</td>
                    <td>Cairo - Riyadh - Dubai</td>
                </tr>

                <tr onclick="window.location='flight-details.php?id=202'">
                    <td>202</td>
                    <td>Cairo → Paris</td>
                    <td>Cairo - Rome - Paris</td>
                </tr>
            </tbody>
        </table>
    </section>

</main>

</body>
</html>

<?php
require_once('../../includes/footer.php');

?>
