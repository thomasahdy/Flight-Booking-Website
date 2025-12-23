<?php

require_once('frontend/includes/header.php');
?>
<main>
    <form class="form-container">
        <h1 class="form-header">Welcome back, Login now!</h1>
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
        <button class="form-action-btn">Login</button>
        <p class="">Don't have an account?
            <a href="/signup"><span class=""><u>Signup here</u></span>    </a>
        </p>
    </form>
</main>
<?php

require_once('frontend/includes/footer.php');
?>