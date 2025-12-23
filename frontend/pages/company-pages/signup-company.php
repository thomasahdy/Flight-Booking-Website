<?php require_once('../../includes/header.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Company Register</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/auth-form.css">
</head>

<body>
<main>
<form class="form-container" method="POST" enctype="multipart/form-data">

    <h1 class="form-header">Create account as a company</h1>

    <div class="form-group">
        <label>Company Name</label>
        <input type="text" name="company_name" required>
    </div>

    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" required>
    </div>
    <div class="form-group">
        <label>Bio</label>
        <textarea name="bio" rows="3" required></textarea>
    </div>
    <div class="form-group">
        <label>Address</label>
        <input type="text" name="address" required>
    </div>
       <div class="form-group">
        <label>Location (optional)</label>
        <input type="text" name="location">
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
        <label>Bio</label>
        <textarea name="bio" rows="3" required></textarea>
    </div>

    <div class="form-group">
        <label>Address</label>
        <input type="text" name="address" required>
    </div>

    <div class="form-group">
        <label>Location (optional)</label>
        <input type="text" name="location">
    </div>

    <div class="form-group">
        <label>Logo Image</label>
        <input type="file" name="logoImg" accept="image/*" required>
    </div>

    <div class="form-action-btn-container">
        <button type="submit" class="form-action-btn">Register</button>
    </div>

</form>
</main>
</body>
</html>

<?php require_once('../../includes/footer.php'); ?>
