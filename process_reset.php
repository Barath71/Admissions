<?php
require_once 'db.php';

$username = $_POST['username'] ?? '';
$otp = $_POST['otp'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

if (!$username || !$otp || !$newPassword || !$confirmPassword) {
  die("All fields are required.");
}

if ($newPassword !== $confirmPassword) {
  die("Passwords do not match.");
}

// Check OTP
$result = $conn->query("SELECT otp FROM users WHERE username = '$username'");
if ($result->num_rows !== 1) {
  die("User not found.");
}

$row = $result->fetch_assoc();
if ($row['otp'] !== $otp) {
  die("Invalid OTP.");
}

// Hash password using SHA-256
$hashedPassword = hash('sha256', $newPassword);


$update = $conn->query("UPDATE users SET password = '$hashedPassword', otp = NULL WHERE username = '$username'");

if ($update) {
  echo "Password changed successfully. <a href='login.php'>Go to login</a>";
} else {
  echo "Failed to update password.";
}
?>
