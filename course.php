<?php

require_once 'db.php';

session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: admin.html"); // Redirect to login if not logged in
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin panel - Course Management</title>
      <link rel="stylesheet" href="course.css">  
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
  <a href="#">📚 Course Management</a>
</div>

<div class="main" id="main">
<div class="header">
  <h1>Course</h1>
  <div class="filters">
    <button onclick="openModal()">+ Add Course</button>
  </div>
</div>

<div class="modal" id="addCourseModal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2>Add New Course</h2>
    <form action="add_course.php" method="POST">
      <label>Course Name</label>
      <input type="text" name="course" required>

      <label>Description</label>
      <textarea name="description" rows="3" required></textarea>

      <label>Duration</label>
      <input type="text" name="duration" required>

      <div class="modal-buttons">
        <button type="button" onclick="closeModal()">Close</button>
        <button type="submit">Add Course</button>
      </div>
    </form>
  </div>
</div>

<div class="table-container">
  <table>
    <thead>
      <tr>
        <th></th>
        <th>Course</th>
        <th>Description</th>
        <th>Duration</th>
        <th>Active Status</th>
      </tr>
    </thead>
    <tbody>
  <?php
$conn = new mysqli("localhost", "root", "", "admissions");
$result = $conn->query("SELECT * FROM courses");

while ($row = $result->fetch_assoc()) {
    $row_json = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
    echo "<tr>
        <td>
          <button class='edit-btn' onclick=\"openEditModal($row_json)\">✎</button>
          <form method='post' action='delete_course.php' style='display:inline'>
            <input type='hidden' name='id' value='" . $row['id'] . "'>
            <button class='delete-btn' onclick='return confirm(\"Are you sure?\")'>🗑</button>
          </form>
        </td>
        <td>" . htmlspecialchars($row['course']) . "</td>
        <td>" . htmlspecialchars($row['description']) . "</td>
        <td>" . htmlspecialchars($row['duration']) . "</td>
        <td><input type='checkbox' " . ($row['active_status'] == 0 ? "checked" : "") . " disabled></td>
      </tr>";
}
$conn->close();
?>
    </tbody>
  </table>
</div>

  <div class="modal" id="editCourseModal">
  <div class="modal-content">
    <span class="close" onclick="closeEditModal()">&times;</span>
    <h2>Edit Course</h2>
    <form method="POST" action="update_course.php">
      <input type="hidden" name="id" id="edit_id">

      <label>Course Name</label>
      <input type="text" name="course" id="edit_course" required>

      <label>Description</label>
      <textarea name="description" id="edit_description" rows="3" required></textarea>

      <label>Duration</label>
      <input type="text" name="duration" id="edit_duration" required>

      <div class="modal-buttons">
        <button type="button" onclick="closeEditModal()">Close</button>
        <button type="submit">Save Changes</button>
      </div>
    </form>
  </div>
</div>

  
</div>

<script>
  function openModal() {
    document.getElementById("addCourseModal").style.display = "block";
  }
  function closeModal() {
    document.getElementById("addCourseModal").style.display = "none";
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

<script>
function openEditModal(data) {
  document.getElementById('edit_id').value = data.id;
  document.getElementById('edit_course').value = data.course;
  document.getElementById('edit_description').value = data.description;
  document.getElementById('edit_duration').value = data.duration;

  document.getElementById('editCourseModal').style.display = 'block';
}

function closeEditModal() {
  document.getElementById('editCourseModal').style.display = 'none';
}

// Optional: Close modal if clicked outside the modal content
window.onclick = function(event) {
  const modal = document.getElementById('editCourseModal');
  if (event.target === modal) {
    modal.style.display = "none";
  }
}
</script>

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
</script>

  </div>
</body>
</html>