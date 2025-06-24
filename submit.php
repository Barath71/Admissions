<?php

require_once 'db.php';


// Create uploads directory if it doesn't exist
$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Handle file uploads
$marksheet = $_FILES['marksheet']['name'];
$marksheet_tmp = $_FILES['marksheet']['tmp_name'];
$marksheet_ext = strtolower(pathinfo($marksheet, PATHINFO_EXTENSION));
$marksheet_path = $uploadDir . uniqid("marksheet_") . "." . $marksheet_ext;

$photo = $_FILES['photo']['name'];
$photo_tmp = $_FILES['photo']['tmp_name'];
$photo_ext = strtolower(pathinfo($photo, PATHINFO_EXTENSION));
$photo_path = $uploadDir . uniqid("photo_") . "." . $photo_ext;

// File size validations
$marksheet_size = $_FILES['marksheet']['size'];
$photo_size = $_FILES['photo']['size'];

if ($marksheet_ext !== "pdf" || $marksheet_size > 2 * 1024 * 1024) {
    die("Invalid marksheet file. Must be a PDF under 2MB.");
}
if (!in_array($photo_ext, ['jpg', 'jpeg', 'png']) || $photo_size > 500 * 1024) {
    die("Invalid photo. Must be JPG/PNG under 500KB.");
}

// Move uploaded files
if (!move_uploaded_file($marksheet_tmp, $marksheet_path)) {
    die("Failed to upload marksheet.");
}
if (!move_uploaded_file($photo_tmp, $photo_path)) {
    die("Failed to upload photo.");
}

// Collect form data
$first_name    = htmlspecialchars($_POST['firstName'] ?? '', ENT_QUOTES, 'UTF-8');
$last_name     = htmlspecialchars($_POST['lastName'] ?? '', ENT_QUOTES, 'UTF-8');
$gender        = htmlspecialchars($_POST['gender'] ?? '', ENT_QUOTES, 'UTF-8');
$email         = htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8');
$phone         = htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8');
$dob           = htmlspecialchars($_POST['dob'] ?? '', ENT_QUOTES, 'UTF-8');
$address       = htmlspecialchars($_POST['address'] ?? '', ENT_QUOTES, 'UTF-8');
$group         = htmlspecialchars($_POST['groupSelect'] ?? '', ENT_QUOTES, 'UTF-8');
$total_marks   = htmlspecialchars($_POST['marks'] ?? '', ENT_QUOTES, 'UTF-8');
$course        = htmlspecialchars($_POST['courseSelect'] ?? '', ENT_QUOTES, 'UTF-8');


// Insert into DB
$sql = "INSERT INTO applications 
    (first_name, last_name, gender, email, phone, dob, address, group_name, total_marks, course, marksheet_path, photo_path)
    VALUES 
    ('$first_name', '$last_name', '$gender', '$email', '$phone', '$dob', '$address', '$group', '$total_marks', '$course', '$marksheet_path', '$photo_path')";

if ($conn->query($sql) === TRUE) {
    header("Location: Response.html");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
