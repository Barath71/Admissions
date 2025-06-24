<?php

require_once 'db.php';


$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$course = trim($_POST['course']);
$description = trim($_POST['description']);
$duration = trim($_POST['duration']);


if ($id > 0 && $course && $description && $duration) {
    $stmt = $conn->prepare("UPDATE courses SET course = ?, description = ?, duration = ? WHERE id = ?");
    $stmt->bind_param("sssi", $course, $description, $duration, $id);

    if ($stmt->execute()) {
        header("Location: course.php?message=Course+updated+successfully");
        exit;
    } else {
        echo "Error updating course: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid data provided.";
}

$conn->close();
?>
