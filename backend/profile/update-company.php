<?php
require_once "../middleware/authGuard.php";
require_once "../config/database.php";

$db = getDBConnection();
$stmt = $db->prepare(
  "UPDATE companies SET bio=?, address=? WHERE user_id=?"
);
$stmt->execute([$_POST['bio'], $_POST['address'], $_SESSION['user_id']]);

echo json_encode(["success" => true]);