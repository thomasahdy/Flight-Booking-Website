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
    <link rel="stylesheet" href="../../css/auth-form.css">
</head>
<body>

<main class="company-profile">

    
    <div class="profile-header">
        <img src="<?= $user['photo'] ?>" id="userPhoto" class="profile-logo" alt="user profile">
        <h1 class="profile-name" id="userPhoto"><?= $user['name'] ?></h1>
    </div>

    <!-- ===== Info ===== -->
    <div class="profile-info">
        <div class="info-item">
            <label>Email</label>
            <p id="userEmail"><?= $user['email'] ?></p>
        </div>

        <div class="info-item">
            <label>Telephone</label>
            <p id="userTel"><?= $user['telephone'] ?></p>
        </div>
        <div class="info-item">
            <label>Passport image</label>
            <img id="userPassportImg" src="<?= $user['passportImg'] ?>" class="" alt="user passport image">
        </div>
    </div>

    <!-- ===== Actions ===== -->
    <div class="profile-actions">
        <a href="edit-user-profile.php">
            <button>Edit Profile</button>
        </a>
    </div>

    <form class="form-container" onsubmit="handleEditUser(event)"> 
        <div class="form-group">
            <div class>
            <label for="email">Email</label>
            <input type="email" name="email" id="email">
            </div>
            <div>
            <label for="name">Name</label>
            <input type="text" name="name" id="name">
            </div>
            <div>
            <label for="tel">Telephone</label>
            <input type="tel" name="tel" id="tel">
            </div>
            
            <div >
        <label>Username</label>
        <input type="text" name="name" id="username" required>
        </div>

    <div>
        <label>Profile Photo</label>
        <input type="file" name="photo" accept="image/*" id="photo" required>
    </div>

    <div >
        <label>Passport Image</label>
        <input type="file" name="passportImg" accept="image/*" id="passportImg" required>
    </div>

        </div>
        <div class="form-action-btn-container">
        <button type="submit" class="form-action-btn">Confirm</button>
    </div>
    </form>

</main>
<script src="../../js/user-profile.js"></script>
<script src="../../js/auth.js"></script>
</body>
</html>

<?php
require_once('../../includes/footer.php');

?>
