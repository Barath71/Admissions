<?php
require_once 'db.php';

$username = $_POST['username'] ?? '';

if ($username) {
  $stmt = $conn->prepare("SELECT id FROM admins WHERE username = ? LIMIT 1");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    echo "exists";
  } else {
    echo "not_found";
  }

  $stmt->close();
}
?>
