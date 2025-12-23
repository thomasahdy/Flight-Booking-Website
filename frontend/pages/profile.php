<?php
session_start();
require_once('../includes/header.php');

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userType = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null;

// Mock user data - replace with actual database queries
$user = null;
if ($isLoggedIn) {
    if ($userType === 'company') {
        $user = [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['company_name'] ?? 'Company Name',
            'email' => $_SESSION['email'] ?? 'company@example.com',
            'bio' => 'International airline company providing high quality flights.',
            'address' => 'Cairo, Egypt',
            'logo' => '../assets/logo.png',
            'type' => 'company'
        ];
    } else {
        $user = [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['name'] ?? 'User Name',
            'email' => $_SESSION['email'] ?? 'user@example.com',
            'telephone' => $_SESSION['telephone'] ?? '0123456789',
            'photo' => '../assets/logo.png',
            'passportImg' => '../assets/logo.png',
            'type' => 'passenger'
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/companyprofile.css">
    <style>
        .profile-container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 24px;
        }
        .login-prompt {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 48px 24px;
            text-align: center;
        }
        .login-prompt h2 {
            color: var(--purple-blue);
            margin-bottom: 16px;
        }
        .login-prompt p {
            color: #666;
            margin-bottom: 24px;
            font-size: 1.1rem;
        }
        .login-prompt a {
            display: inline-block;
            text-decoration: none;
        }
    </style>
</head>
<body>

<main class="profile-container">
    <?php if (!$isLoggedIn): ?>
        <!-- Not Logged In -->
        <div class="login-prompt">
            <h2>Please Log In</h2>
            <p>You need to be logged in to view your profile.</p>
            <a href="/Flight-Booking-Website-main/index.php" class="btn btn-primary" style="text-decoration: none;">
                Go to Login
            </a>
        </div>

    <?php elseif ($userType === 'company' && $user): ?>
        <!-- Company Profile -->
        <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div class="profile-header">
                <img src="<?= htmlspecialchars($user['logo']); ?>" class="profile-logo" alt="Company Logo">
                <h1 class="profile-name"><?= htmlspecialchars($user['name']); ?></h1>
            </div>

            <div class="profile-info">
                <div class="info-item">
                    <label>Email</label>
                    <p><?= htmlspecialchars($user['email']); ?></p>
                </div>

                <div class="info-item">
                    <label>Bio</label>
                    <p><?= htmlspecialchars($user['bio']); ?></p>
                </div>

                <div class="info-item">
                    <label>Address</label>
                    <p><?= htmlspecialchars($user['address']); ?></p>
                </div>
            </div>

            <div class="profile-actions">
                <a href="company-pages/companyhome.php">
                    <button class="btn btn-primary">Back to Home</button>
                </a>
                <a href="/Flight-Booking-Website-main/index.php">
                    <button class="btn btn-secondary" style="margin-left: 8px;">Logout</button>
                </a>
            </div>
        </div>

    <?php elseif ($userType === 'passenger' && $user): ?>
        <!-- Passenger Profile -->
        <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div class="profile-header">
                <img src="<?= htmlspecialchars($user['photo']); ?>" class="profile-logo" alt="User Profile">
                <h1 class="profile-name"><?= htmlspecialchars($user['name']); ?></h1>
            </div>

            <div class="profile-info">
                <div class="info-item">
                    <label>Email</label>
                    <p><?= htmlspecialchars($user['email']); ?></p>
                </div>

                <div class="info-item">
                    <label>Telephone</label>
                    <p><?= htmlspecialchars($user['telephone']); ?></p>
                </div>

                <div class="info-item">
                    <label>Passport Image</label>
                    <img src="<?= htmlspecialchars($user['passportImg']); ?>" alt="Passport" style="max-width: 300px; border-radius: 4px;">
                </div>
            </div>

            <div class="profile-actions">
                <a href="user-pages/userhome.php">
                    <button class="btn btn-primary">Back to Home</button>
                </a>
                <a href="/Flight-Booking-Website-main/index.php">
                    <button class="btn btn-secondary" style="margin-left: 8px;">Logout</button>
                </a>
            </div>
        </div>

    <?php else: ?>
        <!-- Unknown User Type -->
        <div class="login-prompt">
            <h2>Invalid Session</h2>
            <p>Your session is invalid or expired. Please log in again.</p>
            <a href="/Flight-Booking-Website-main/index.php" class="btn btn-primary" style="text-decoration: none;">
                Go to Login
            </a>
        </div>

    <?php endif; ?>
</main>

</body>
</html>

<?php require_once('../includes/footer.php'); ?>
