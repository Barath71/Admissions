<?php

require_once 'db.php';


$course = $_POST['course'];
$description = $_POST['description'];
$duration = $_POST['duration'];


$sql = "INSERT INTO courses (course, description, duration, active_status) VALUES (?, ?, ?, 0)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $course, $description, $duration);

if ($stmt->execute()) {
    header("Location: course.php?msg=Course added successfully");
    exit();
} else {
    echo "Error: " . $stmt->error;
}
$conn->close();
?>
