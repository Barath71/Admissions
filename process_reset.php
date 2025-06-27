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
$result = $conn->query("SELECT otp FROM admins WHERE username = '$username'");
if ($result->num_rows !== 1) {
  die("User not found.");
}

$row = $result->fetch_assoc();
if ($row['otp'] !== $otp) {
  die("Invalid OTP.");
}


$hashedPassword = hash("sha256", $newPassword);


$update = $conn->query("UPDATE admins SET password = '$hashedPassword', otp = NULL WHERE username = '$username'");

if ($update) {
    echo "<script>alert('Password changed successfully.'); window.location.href='Reset_Password.php';</script>";
} else {
    echo "<script>alert('Failed to update password.'); window.location.href='Reset_Password.php';</script>";
}

?>
