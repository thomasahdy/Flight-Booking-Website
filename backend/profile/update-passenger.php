<?php
require_once "../middleware/authGuard.php";
require_once "../config/database.php";

$db = getDBConnection();
$stmt = $db->prepare(
  "UPDATE passengers SET photo=? WHERE user_id=?"
);
$stmt->execute([$_POST['photo'], $_SESSION['user_id']]);

echo json_encode(["success" => true]);
