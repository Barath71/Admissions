<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name     = trim($_POST['name']);
  $mobile   = trim($_POST['mobile']);
  $email    = trim($_POST['email']);
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  // Check if any field is empty
  if (empty($name) || empty($mobile) || empty($email) || empty($username) || empty($password)) {
    echo "<script>alert('All fields are required.'); history.back();</script>";
    exit;
  }

  // Hash password using SHA-256
  $hashedPassword = hash("sha256", $password);

  // Insert into DB
  $stmt = $conn->prepare("INSERT INTO admins (name, mobile, email, username, password) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $name, $mobile, $email, $username, $hashedPassword);

  if ($stmt->execute()) {
    echo "<script>alert('Admin registered successfully.'); window.location.href='admin.html';</script>";
  } else {
    echo "<script>alert('Registration failed. Username might already exist.'); history.back();</script>";
  }

  $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Registration</title>
  <style>
    body {
      font-family: Arial;
      background: #f0f0f0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .form-box {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px gray;
      width: 350px;
    }
    .form-box h2 {
      text-align: center;
    }
    .form-box .field {
      margin-bottom: 15px;
    }
    .form-box .field label {
      display: block;
      margin-bottom: 5px;
    }
    .form-box .field input {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

.buttons {
  display: flex;
  justify-content: space-between;
}
    .buttons button {
      background-color: #add8e6;
      border: none;
      padding: 10px 25px;
      font-size: 16px;
      color: white;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.2s;
    }

    .buttons button:hover {
      background-color: #007bff;
      transform: scale(1.05);
    }

  </style>
</head>
<body>
  <div class="form-box">
    <h2>Admin Registration</h2>
    <form id="registerForm" method="POST">
      <div class="field">
        <label>Name</label>
        <input type="text" name="name" id="name">
      </div>
      <div class="field">
        <label>Mobile Number</label>
        <input type="text" name="mobile" id="mobile">
      </div>
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" id="email">
      </div>
      <div class="field">
        <label>Username</label>
        <input type="text" name="username" id="username" placeholder="Create Username">
      </div>
      <div class="field">
        <label>Password</label>
        <input type="password" name="password" id="password" placeholder="Create Password">
      </div>
      <div class="buttons">
      <button type="submit">Submit</button>
      <a href="admin.html"><button type="button">Login</button></a>
      </div>
    </form>
  </div>

  <script>
    // JS validation before submitting
    document.getElementById("registerForm").addEventListener("submit", function(e) {
      const fields = ["name", "mobile", "email", "username", "password"];
      for (let id of fields) {
        let field = document.getElementById(id);
        if (!field.value.trim()) {
          alert("Please fill out " + id + " field.");
          field.focus();
          e.preventDefault();
          return;
        }
      }
    });
  </script>
</body>
</html>
