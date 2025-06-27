<?php
require 'db.php';

$id = $_POST['id'] ?? null;
$status = $_POST['active_status'] ?? null;

if ($id !== null && $status !== null) {
    $id = intval($id);
    $status = intval($status);

    $sql = "UPDATE courses SET active_status = $status WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Success";
    } else {
        echo "DB Error: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
