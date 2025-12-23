<?php

require_once('../includes/header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>signup</title>
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
            <label for="name">Name</label>
            <input type="text" name="name">
            </div>
            <div>
            <label for="tel">Telephone</label>
            <input type="tel" name="tel">
            </div>
            <div>
            <label for="email">Type</label>
            <select id="type" name="type" required>
                    <option value="">Select account type</option>
                    <option value="company">Company</option>
                    <option value="passenger">Passenger</option>
            </select>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password">
            </div>
        </div>
        <div class="form-action-btn-container">
            <button class="form-action-btn" style="width: 7rem;">Continue ></button>
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
