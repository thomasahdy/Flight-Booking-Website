<?php
require_once('../../includes/header.php');



$companyName = "Sky Airlines";
$companyLogo = "../assets/logo.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Home</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/companyhome.css">
</head>
<body>

<main class="company-home">

    
    <div class="company-header">
        <img src="<?= $companyLogo ?>" class="company-logo" alt="Company Logo">
        <h1 class="company-name"><?= $companyName ?></h1>
    </div>

    
    <div class="company-actions">
        <div class="profile-actions">
            <a href="addflight.php">
             <button class="form-action-btn">Add Flight</button>
             </a>
        </div>

        <a href="companyprofile.php"><button>Profile</button></a>
    </div>

    
    <section class="flights-section">
        <h2>My Flights</h2>

        <table class="flights-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Itinerary</th>
                    <th>Registered</th>
                    <th>Pending</th>
                </tr>
            </thead>
            <tbody>
                
                <tr onclick="window.location=''">
                    <td>101</td>
                    <td>Cairo → Dubai</td>
                    <td>Cairo - Riyadh - Dubai</td>
                    <td>35</td>
                    <td>5</td>
                </tr>

                <tr onclick="window.location=''">
                    <td>102</td>
                    <td>Cairo → Paris</td>
                    <td>Cairo - Rome - Paris</td>
                    <td>40</td>
                    <td>2</td>
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
