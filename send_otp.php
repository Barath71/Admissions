<?php
require_once 'db.php';
require 'vendor/autoload.php'; 

require __DIR__ . '/vendor/autoload.php';




$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$username = $_POST['username'] ?? '';

if (empty($username)) {
    echo "Username is missing.";
    exit;
}


$username = $conn->real_escape_string($username);

$result = $conn->query("SELECT email FROM admins WHERE username = '$username'");

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $otp = rand(100000, 999999);

  
    $conn->query("UPDATE admins SET otp = '$otp' WHERE username = '$username'");

   
    $mail = new PHPMailer(true);
 try {
    $mail->isSMTP();
    $mail->Host       = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['SMTP_USER'];
    $mail->Password   = $_ENV['SMTP_PASS'];
    $mail->SMTPSecure = $_ENV['SMTP_SECURE'];
    $mail->Port       = $_ENV['SMTP_PORT'];

    $mail->setFrom($_ENV['SMTP_USER'], $_ENV['SMTP_FROM_NAME']);
    $mail->addAddress($email);  // Assuming $email is defined
    $mail->Subject = 'Your OTP Code';
    $mail->Body    = "Hello $username,\n\nYour OTP is: $otp\n\nThank you."; // Assuming $username & $otp are defined

    $mail->send();
    echo "OTP sent successfully to $email";
} catch (Exception $e) {
    echo "Failed to send OTP. Error: " . $mail->ErrorInfo;
}
} else {
    echo "Username not found.";
}
