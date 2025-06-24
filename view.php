<?php

require_once 'db.php';


$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    die("Invalid Application ID.");
}

$sql = "SELECT * FROM applications WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$app = $result->fetch_assoc();

if (!$app) {
    die("Application not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Application Details</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #e9eef2;
    padding: 40px;
    margin: 0;
  }

  .container {
    background: #fff;
    padding: 15px 40px;
    border-radius: 12px;
    max-width: 850px;
    margin: auto;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  }

  h2 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
  }

  .field {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
  }

  .label {
    font-weight: bold;
    color: #555;
    width: 200px;
  }

  .value {
    color: #333;
    flex-grow: 1;
  }

 .card-row {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-top: 30px;
  justify-content: space-between;
}

.card {
  background: #ffffff;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  padding: 20px;
  flex: 1;
  min-width: 320px;
}

.btn-download {
  display: inline-block;
  margin-top: 10px;
  padding: 8px 16px;
  background-color: #1b5d2b;
  color: white;
  text-decoration: none;
  border-radius: 6px;
  transition: background-color 0.3s ease;
}

.btn-download:hover {
  background-color: lightgreen;
  color: black;
}

.decision-section select {
  padding: 8px;
  margin-top: 10px;
  width: 100%;
  border-radius: 6px;
  border: 1px solid #ccc;
}

.decision-section .buttons {
  margin-top: 15px;
}

.decision-section button {
  background-color: #1b5d2b;
  color: white;
  border: none;
  padding: 10px 18px;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.decision-section button:hover{
  background-color: lightgreen;
  color: black;
  transition: background 0.3s ease;
}
</style>

</head>
<body>
<div class="container">
  <h2>Application Details</h2>

  <div class="field"><div class="label">Full Name:</div><div class="value"><?= htmlspecialchars($app['first_name'] . " " . $app['last_name']) ?></div></div>
  <div class="field"><div class="label">Email:</div><div class="value"><?= htmlspecialchars($app['email']) ?></div></div>
  <div class="field"><div class="label">Phone:</div><div class="value"><?= htmlspecialchars($app['phone']) ?></div></div>
  <div class="field"><div class="label">Gender:</div><div class="value"><?= htmlspecialchars($app['gender']) ?></div></div>
  <div class="field"><div class="label">DOB:</div><div class="value"><?= htmlspecialchars($app['dob']) ?></div></div>
  <div class="field"><div class="label">Group:</div><div class="value"><?= htmlspecialchars($app['group_name']) ?></div></div>
  <div class="field"><div class="label">Course:</div><div class="value"><?= htmlspecialchars($app['course']) ?></div></div>
  <div class="field"><div class="label">Total Marks:</div><div class="value"><?= htmlspecialchars($app['total_marks']) ?></div></div>
  <div class="field"><div class="label">Address:</div><div class="value"><?= htmlspecialchars($app['address']) ?></div></div>
  <div class="field"><div class="label">Submitted On:</div><div class="value"><?= htmlspecialchars($app['created_at']) ?></div></div>

<!-- Photo and Marksheet Section -->
<div class="card-row">
  <div class="card">
    <div class="label">Photo</div>
    <div class="value">
<img src="<?= $app['photo_path'] ?>" width="80%" justify-content="center" height="200px" style="object-fit: cover; border-radius: 50%; border: 3px solid #ccc; box-shadow: 0 4px 8px rgba(0,0,0,0.1);" />
      <br>
      <a href="<?= $app['photo_path'] ?>" class="btn-download" download>Download Photo</a>
    </div>
  </div>

  <div class="card">
    <div class="label">Marksheet</div>
    <div class="value">
      <embed src="<?= $app['marksheet_path'] ?>" width="100%" height="200px" type="application/pdf" />
      <br>
      <a href="<?= $app['marksheet_path'] ?>" class="btn-download" download>Download Marksheet</a>
    </div>
  </div>
</div>

<!-- Status Update Card -->
<div class="card decision-section">
  <form method="post" action="update_status.php">
    <input type="hidden" name="application_id" value="<?= $app['id'] ?>">
    <div class="label">Application Decision:</div>
    <select name="status" id="status">
      <option value="1" <?= $app['status'] == 1 ? 'selected' : '' ?>>Pending</option>
      <option value="2" <?= $app['status'] == 2 ? 'selected' : '' ?>>Accepted</option>
      <option value="3" <?= $app['status'] == 3 ? 'selected' : '' ?>>Rejected</option>
    </select>
    <div class="buttons">
      <button type="submit">Update Status</button>
    </div>
  </form>
</div>

<!-- Back to Dashboard -->
<div style="text-align:center; margin-top: 20px;">
  <a href="Dashboard.php" class="btn-download">← Back to Dashboard</a>
</div>

</div>

</body>
</html>
