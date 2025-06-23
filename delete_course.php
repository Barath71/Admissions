<?php
// delete_course.php

// Database connection
require_once 'db.php';

// Check if ID is received via POST
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect back to the page after successful deletion
        header("Location: course.php?message=Course deleted successfully");
        exit();
    } else {
        echo "Error deleting course: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No course ID received.";
}

$conn->close();
?>
