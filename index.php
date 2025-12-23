<?php

require_once('frontend/includes/header.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="frontend/css/styles.css">
    <link rel="stylesheet" href="frontend/css/auth-form.css">
</head>
<body>
    <main>
    <form class="form-container" onsubmit="handleLogin(event)">
        <h1 class="form-header">Welcome back, Login now!</h1>
        <div class="form-group">
            <div>
            <label for="email">Email</label>
            <input type="email" name="email">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password">
            </div>
        </div>
        <div class="form-action-btn-container">
            <button class="form-action-btn" type="submit">Login</button>
        </div>
        <p class="">Don't have an account?
            <a href="frontend/pages/signup.php"><span class=""><u>Signup here</u></span></a>
        </p>
    </form>
</main>
<script src="frontend/js/auth.js"></script>
</body>
</html>

<?php

require_once('frontend/includes/footer.php');
?>