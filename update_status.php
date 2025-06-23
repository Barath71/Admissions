<?php
require_once 'db.php';


// Fix: Get correct POST field names
$id = $_POST['application_id']; // Corrected name
$status = $_POST['status'];

// Sanitize and validate
$id = intval($id);
$status = intval($status);

// Prepare and execute update query
$sql = "UPDATE applications SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $status, $id);

if ($stmt->execute()) {
    header("Location: dashboard.php?message=Status updated successfully");
    exit();
} else {
    echo "Error updating status: " . $stmt->error;
}

$conn->close();
?>
