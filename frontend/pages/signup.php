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
    <form class="form-container" onsubmit="continueRegisteration(event)"> 
        <h1 class="form-header">Create account</h1>
        <div class="form-group">
            <div>
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
            <div>
            <label for="type">Type</label>
            <select id="type" name="type" required>
                    <option value="passenger">Passenger</option>
                    <option value="company">Company</option>
            </select>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password">
            </div>
        </div>
        <div class="form-action-btn-container">
           <button type="submit" class="form-action-btn" style="width: 7rem;">
             Continue >
            </button>

        </div>
    </form>
</main>
<script src="../js/signup.js"></script>
    
</body>
</html>

<?php

require_once('../includes/footer.php');
?>
