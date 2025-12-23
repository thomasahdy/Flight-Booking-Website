<?php

require_once('../includes/header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/auth-form.css">
    
</head>
<body>
    <main>
    <form class="form-container">
        <h1 class="form-header">Create account</h1>
        <div class="form-group">
            <div>
            <label for="email">Email</label>
            <input type="email" name="email">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="text" name="password">
            </div>
        </div>
        <div class="form-action-btn-container">
            <button class="form-action-btn">Login</button>
        </div>
        <p class="">Don't have an account?
            <a href="frontend\register.html"><span class=""><u>Signup here</u></span>    </a>
        </p>
    </form>
</main>
    
</body>
</html>

<?php

require_once('../includes/footer.php');
?>
