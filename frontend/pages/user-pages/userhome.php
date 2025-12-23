<?php
require_once('../../includes/header.php');


$user = [
    'name' => 'Basmala',
    'email' => 'basmala@gmail.com',
    'photo' => '../assets/logo.png',
    'telephone' => '0123456789',
    'passportImg' => '../assets/logo.png'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Home</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/companyhome.css">
    <link rel="stylesheet" href="../../css/companyprofile.css">

</head>
<body>

<main class="company-home">

    
    <div class="company-header">
        <img src="<?= $user['photo'] ?>" class="company-logo" alt="User photo">
        <h1 class="company-name"><?= $user['name'] ?></h1>
    </div>
    

    <div class="profile-info">
        <div class="info-item">
            <label>Email</label>
            <p><?= $user['email'] ?></p>
        </div>

        <div class="info-item">
            <label>Telephone</label>
            <p><?= $user['telephone'] ?></p>
        </div>
        <div class="info-item">
            <label>Passport image</label>
            <img src="<?= $user['passportImg'] ?>" class="" alt="user passport image">
        </div>
    </div>

    
    <div class="company-actions">
        <div class="profile-actions">
            <a href="addflight.php">
             <button class="form-action-btn">Search Flight</button>
             </a>
        </div>

        <a href="userprofile.php"><button>Profile</button></a>
        <a href="messages.php"><button>Messages</button></a>
    </div>
    
    <section class="flights-section">
        <h2>My Current Flights</h2>

        <table class="flights-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Itinerary</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                
                <tr onclick="window.location=''">
                    <td>101</td>
                    <td>Cairo → Dubai</td>
                    <td>Cairo - Riyadh - Dubai</td>
                    <td>1/1/2025</td>
                </tr>

                <tr onclick="window.location=''">
                    <td>102</td>
                    <td>Cairo → Paris</td>
                    <td>Cairo - Rome - Paris</td>
                    <td>2/1/2025</td>
                </tr>
            </tbody>
        </table>
    </section>

    <br>
    
    <section class="flights-section">
        <h2>My Completed Flights</h2>

        <table class="flights-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Itinerary</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                
                <tr onclick="window.location=''">
                    <td>101</td>
                    <td>Cairo → Dubai</td>
                    <td>Cairo - Riyadh - Dubai</td>
                    <td>1/1/2025</td>
                </tr>

                <tr onclick="window.location=''">
                    <td>102</td>
                    <td>Cairo → Paris</td>
                    <td>Cairo - Rome - Paris</td>
                    <td>2/1/2025</td>
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
