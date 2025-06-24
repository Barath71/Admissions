<?php
require_once 'db.php';



$id = $_POST['application_id']; 
$status = $_POST['status'];


$id = intval($id);
$status = intval($status);


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
