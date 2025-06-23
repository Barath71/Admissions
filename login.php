<?php
session_start();

require_once 'db.php';


// Check if login form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $user = $_POST['UserName'];
  $pass = $_POST['Password'];

  // Use prepared statements to avoid SQL injection
  $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? LIMIT 1");
  $stmt->bind_param("s", $user);
  $stmt->execute();
  $result = $stmt->get_result();

  // If user found
  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // Verify password (plaintext comparison for now; see notes below for hashed passwords)
    if ($pass === $row['password']) {
      $_SESSION['admin'] = $row['username'];
      header("Location: Dashboard.php"); // Redirect to admin dashboard
      exit();
    } else {
      echo "<script>alert('Incorrect password'); window.history.back();</script>";
    }
  } else {
    echo "<script>alert('User not found'); window.history.back();</script>";
  }
}
$conn->close();
?>
