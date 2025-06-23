<?php

require_once 'db.php';

session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: admin.html"); // Redirect to login if not logged in
  exit();
}

// Fetch counts
$status_counts = [
  'pending' => 0,
  'accepted' => 0,
  'rejected' => 0
];

$countSql = "SELECT status, COUNT(*) as total FROM applications GROUP BY status";
$countResult = $conn->query($countSql);

while ($row = $countResult->fetch_assoc()) {
    if ($row['status'] == 1) $status_counts['pending'] = $row['total'];
    if ($row['status'] == 2) $status_counts['accepted'] = $row['total'];
    if ($row['status'] == 3) $status_counts['rejected'] = $row['total'];
}

// Fetch pending applications
$filterSql = "";
$statusName = "All Applications";

if (isset($_GET['status'])) {
    $status = intval($_GET['status']);
    $filterSql = "WHERE status = $status";

    if ($status === 1) $statusName = "Pending Applications";
    elseif ($status === 2) $statusName = "Accepted Applications";
    elseif ($status === 3) $statusName = "Rejected Applications";
}

$sql = "SELECT * FROM applications $filterSql ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
      <link rel="stylesheet" href="Dashboard.css">  
</head>
<body>
  <div class="page-container">
  <div class="navbar">
    <div class="hamburger" onclick="toggleSidebar()">☰</div>
  <div class="profile-dropdown" onclick="toggleProfileDropdown()">
  <span>👤 <?= htmlspecialchars($_SESSION['admin']) ?></span>
  <div class="dropdown-menu" id="profileDropdown" style="display: none;">
    <form action="logout.php" method="post">
      <button type="submit">Logout</button>
    </form>
  </div>
</div>
  </div>

 <div class="sidebar" id="sidebar">
  <h2>🏛 ADMISSIONS</h2>
  <a href="Dashboard.php">🏠 Dashboard</a>
  <a href="course.php">📚 Course Management</a>
</div>

<div class="main" id="main">
<div class="header">
  <h1>Dashboard</h1>
  <div class="filters">
    <a href="Dashboard.php"><button>📋 All Applications</button></a>
    <a href="Dashboard.php?status=1"><button>🟡 Pending</button></a>
    <a href="Dashboard.php?status=2"><button>✅ Accepted</button></a>
    <a href="Dashboard.php?status=3"><button>❌ Rejected</button></a>
  </div>
</div>


  <div class="status-cards">
    <div class="card">
      <h3>Pending Applications</h3>
      <div class="count"><?= $status_counts['pending'] ?? 0 ?></div>
    </div>
    <div class="card">
      <h3>Accepted Applications</h3>
      <div class="count"><?= $status_counts['accepted'] ?? 0 ?></div>
    </div>
    <div class="card">
      <h3>Rejected Applications</h3>
      <div class="count"><?= $status_counts['rejected'] ?? 0 ?></div>
    </div>
  </div>

  <div class="table-container">
    <h2><?= htmlspecialchars($statusName) ?></h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Course</th>
          <th>Status</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
       <?php while ($row = $result->fetch_assoc()): ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']) ?></td>
  <td><?= htmlspecialchars($row['email']) ?></td>
  <td><?= htmlspecialchars($row['course']) ?></td>
  <td>
    <?php
      if ($row['status'] == 1) echo '<span class="status-badge pending">Pending</span>';
      elseif ($row['status'] == 2) echo '<span class="status-badge accepted">Accepted</span>';
      elseif ($row['status'] == 3) echo '<span class="status-badge rejected">Rejected</span>';
    ?>
  </td>
  <td><?= date("M d, Y", strtotime($row['created_at'])) ?></td>
  <td><a href="view.php?id=<?= $row['id'] ?>"><button class="action-btn">View</button></a></td>
</tr>
<?php endwhile; ?>

        <?php else: ?>
          <tr><td colspan="7">No pending applications found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  let sidebarVisible = true;

  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const main = document.getElementById('main');

    sidebarVisible = !sidebarVisible;

    if (sidebarVisible) {
      sidebar.classList.remove('hidden');
      main.classList.remove('full');
    } else {
      sidebar.classList.add('hidden');
      main.classList.add('full');
    }
  }

  function toggleProfileDropdown() {
    const dropdown = document.getElementById("profileDropdown");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
  }

  // Optional: Hide dropdown when clicking outside
  window.addEventListener("click", function(e) {
    if (!e.target.closest('.profile-dropdown')) {
      document.getElementById("profileDropdown").style.display = "none";
    }
  });
</script>

  </div>
</body>
</html>
