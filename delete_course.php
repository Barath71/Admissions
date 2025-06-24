<?php

require_once 'db.php';


if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
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
