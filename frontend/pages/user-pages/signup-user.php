<?php require_once('../../includes/header.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Passenger Register</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/auth-form.css">
</head>

<body>
<main>
<form class="form-container" method="POST" enctype="multipart/form-data">

    <h1 class="form-header">Create account as a passenger</h1>

    <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>

    <div class="form-group">
        <label>Telephone</label>
        <input type="tel" name="tel" required>
    </div>

    <div class="form-group">
        <label>Profile Photo</label>
        <input type="file" name="photo" accept="image/*" required>
    </div>

    <div class="form-group">
        <label>Passport Image</label>
        <input type="file" name="passportImg" accept="image/*" required>
    </div>

    <div class="form-action-btn-container">
        <button type="submit" class="form-action-btn">Register</button>
    </div>

</form>
</main>
</body>
</html>

<?php require_once('../../includes/footer.php'); ?>
