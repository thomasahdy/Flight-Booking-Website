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
        <label>Username</label>
        <input type="text" name="username" required>
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
