<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if (!isset($_FILES['file'])) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    exit;
}

$file = $_FILES['file'];
$type = $_POST['type'] ?? 'general'; // logo, photo, passport

// Validate file
$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
if ($type === 'passport') {
    $allowedTypes[] = 'application/pdf';
}

if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type']);
    exit;
}

// Max 5MB
if ($file['size'] > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'File too large (max 5MB)']);
    exit;
}

try {
    // Create uploads directory if not exists
    $uploadDir = __DIR__ . '/../../uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $url = 'uploads/' . $filename;

        // Update database based on type
        $conn = getDBConnection();

        if ($type === 'logo') {
            $stmt = $conn->prepare("UPDATE companies SET logo_img = ? WHERE user_id = ?");
            $stmt->execute([$url, $_SESSION['user_id']]);
        } else if ($type === 'photo') {
            $stmt = $conn->prepare("UPDATE passengers SET photo = ? WHERE user_id = ?");
            $stmt->execute([$url, $_SESSION['user_id']]);
        } else if ($type === 'passport') {
            $stmt = $conn->prepare("UPDATE passengers SET passport_img = ? WHERE user_id = ?");
            $stmt->execute([$url, $_SESSION['user_id']]);
        }

        echo json_encode([
            'success' => true,
            'url' => $url,
            'filename' => $filename
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Upload failed']);
}
