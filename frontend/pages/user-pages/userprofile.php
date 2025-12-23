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
    <title>User Profile</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/companyprofile.css">
</head>
<body>

<main class="company-profile">

    
    <div class="profile-header">
        <img src="<?= $user['photo'] ?>" class="profile-logo" alt="user profile">
        <h1 class="profile-name"><?= $user['name'] ?></h1>
    </div>

    <!-- ===== Info ===== -->
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

    <!-- ===== Actions ===== -->
    <div class="profile-actions">
        <a href="edit-user-profile.php">
            <button>Edit Profile</button>
        </a>
    </div>

</main>

</body>
</html>

<?php
require_once('../../includes/footer.php');

?>
