<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f0f0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .reset-container {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      width: 350px;
    }
    .reset-container h2 {
      text-align: center;
    }
    .field {
      margin: 15px 0;
    }
    .field input {
      width: calc(100% - 20px);
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .otp-field {
      display: flex;
      gap: 10px;
    }
    .otp-field input {
      flex: 1;
    }
    .otp-field button {
      padding: 10px;
      border: none;
      background: #007BFF;
      color: white;
      border-radius: 5px;
      cursor: pointer;
    }
    .submit-button {
      width: 100%;
      padding: 10px;
      background: green;
      color: white;
      border: none;
      border-radius: 5px;
      margin-top: 15px;
      cursor: pointer;
    }
      .back-button {
      width: 100%;
      padding: 10px;
      background: green;
      color: white;
      border: none;
      border-radius: 5px;
      margin-top: 15px;
      cursor: pointer;
    }
    
    
   

  </style>
</head>
<body>

  <div class="reset-container">
    <h2>Reset Password</h2>

    <form action="process_reset.php" method="POST">
      <?php $username = $_GET['username'] ?? ''; ?>
      <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">

      <div class="field otp-field">
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="button" onclick="sendOTP()">Send OTP</button>
      </div>

      <div class="field">
        <input type="password" name="new_password" placeholder="New Password" required>
      </div>

      <div class="field">
        <input type="password" name="confirm_password" placeholder="Re-Type New Password" required>
      </div>

      <button class="submit-button" type="submit">Submit</button>
     <a href="admin.html"><button class="back-button" type="button">Back To Login</button></a>
    </form>
  </div>

  <script>
    function sendOTP() {
      const username = "<?php echo htmlspecialchars($username); ?>";
      if (!username) {
        alert("Username is missing.");
        return;
      }

      fetch("send_otp.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "username=" + encodeURIComponent(username)
      })
      .then(res => res.text())
      .then(data => alert(data))
      .catch(() => alert("Error sending OTP."));
    }
  </script>

</body>
</html>
