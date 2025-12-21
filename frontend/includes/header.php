<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tripma - Book Your Next Adventure</title>
    <link rel="stylesheet" href="frontend/css/styles.css">
</head>

<body>
    <!-- Promotional Banner -->
    <div
        style="background: var(--purple-dark); color: white; padding: 0.75rem; text-align: center; position: relative;">
        <p style="margin: 0;">Join Tripma today and save up to 20% on your flight using code TRAVEL at checkout.
            Promotion valid for new users only.</p>
        <button onclick="this.parentElement.style.display='none'"
            style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer;">×</button>
    </div>

    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <a href="index.php" class="logo">Tripma</a>
            <ul class="nav-links">
                <li><a href="../search-flight.html">Flights</a></li>
                <li><a href="#">Hotels</a></li>
                <li><a href="#">Packages</a></li>
                <li><a href="frontend\login.html">Sign in</a></li>
                <li><a href="frontend\register.html" class="btn btn-primary">Sign up</a></li>
            </ul>
        </nav>
    </header>