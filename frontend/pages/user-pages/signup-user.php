<?php

require_once('../../includes/header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>signup</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/auth-form.css">
    
</head>
<body>
    <main>
    <form class="form-container" enctype="multipart/form-data"
      onsubmit="submitPassengerForm(event)">
        <h1 class="form-header">Create account as a user</h1>
        <div class="form-group">
            <div>
                <label for="photo">Photo</label>
                <input type="file" name="photo" accept="image/*" required>
            </div>
            <div>
                <label for="passportImg">Passport image</label>
                <input type="file" name="passportImg" accept="image/*" required>
            </div>
            
            
        <div class="form-action-btn-container">
            <button class="form-action-btn" style="width: 7rem;">Register</button>
        </div>
    </form>
</main>
<script src="../../js/signup-user.js"></script>

    
</body>
</html>

<?php

require_once('../../includes/footer.php');
?>