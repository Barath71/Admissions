<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Undergraduate Course Application</title>
  <link rel="stylesheet" href="style.css">   
</head>
<body>
<div class="container">
  <h2>Undergraduate Course Application</h2>
  <form id="appForm" action="submit.php" method="POST" enctype="multipart/form-data">
    <div class="row">
      <div class="field">
        <label>First Name</label>
        <input type="text" name="firstName">
      </div>
      <div class="field">
        <label>Last Name</label>
        <input type="text" name="lastName">
      </div>
    </div>

    <div class="row">
      <div class="field">
        <label>Gender</label>
        <div class="gender">
          <label><input type="radio" name="gender" value="Male"> Male</label>
          <label><input type="radio" name="gender" value="Female"> Female</label>
          <label><input type="radio" name="gender" value="Other"> Other</label>
        </div>
      </div>
      <div class="field">
        <label>Date of Birth</label>
        <input type="date" name="dob">
      </div>
    </div>

    <div class="row">
      <div class="field">
        <label>Email</label>
        <input type="email" name="email">
      </div>
      <div class="field">
        <label>Phone Number</label>
        <input type="text" name="phone">
      </div>
    </div>

    <div class="field full">
      <label>Address</label>
      <textarea name="address"></textarea>
    </div>

    <div class="row">
      <div class="field">
        <label>Group</label>
        <select id="groupSelect" name="groupSelect" onchange="updateCourses()">
          <option>-- Select Group --</option>
          <option value="cse">Computer Science</option>
          <option value="mech">Mechanical Engineering</option>
          <option value="civil">Civil Engineering</option>
          <option value="eee">Electrical and Electronics Engineering</option>
        </select>
      </div>
      <div class="field">
        <label>Total Marks (out of 600)</label>
        <input type="text" name="marks">
      </div>
    </div>

  <div class="field full">
  <label>Select Course</label>
  <select id="courseSelect" name="courseSelect" required>
    <option value="">-- Select Course --</option>
    <?php
    require_once 'db.php';

    $sql = "SELECT course FROM courses WHERE active_status = 0";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<option value='" . htmlspecialchars($row['course'], ENT_QUOTES) . "'>" . htmlspecialchars($row['course']) . "</option>";
      }
    } else {
      echo "<option disabled>No active courses available</option>";
    }
    ?>
  </select>
</div>


    <div class="field full">
      <label>12th Marksheet</label>
      <input type="file" id="marksheet" name="marksheet" accept="application/pdf">
      <span class="note">PDF only, Max size 2 MB</span>
    </div>

    <div class="field full">
      <label>Photo</label>
      <input type="file" id="photo" name="photo" accept="image/png, image/jpeg">
      <span class="note">JPG/PNG only, Max size 500 KB</span>
    </div>

    <div class="field full">
  <label>
    <input type="checkbox" id="acknowledge">
    I acknowledge the information provided is true to my knowledge.
  </label>
</div>

 <div class="buttons">
  <button type="submit">Submit</button>
  <button type="button" onclick="clearForm()">Clear</button>
</div>

  </form>
</div>

<script>

  function resetCourses() {
    updateCourses(); 
  }


document.getElementById("appForm").addEventListener("submit", function (e) {
  const form = this;

 
  const firstName = form.firstName.value.trim();
  const lastName = form.lastName.value.trim();
  const gender = form.querySelector('input[name="gender"]:checked');
  const dob = form.dob.value;
  const email = form.email.value.trim();
  const phone = form.phone.value.trim();
  const address = form.address.value.trim();
  const group = form.groupSelect.value;
  const marks = form.marks.value.trim();
  const course = form.courseSelect.value;
  const marksheet = form.marksheet.files[0];
  const photo = form.photo.files[0];
  const acknowledge = document.getElementById("acknowledge").checked;

  // Validation checks
  if (!firstName) {
    alert("First Name is required.");
    form.firstName.focus();
    e.preventDefault();
    return;
  }
  if (!lastName) {
    alert("Last Name is required.");
    form.lastName.focus();
    e.preventDefault();
    return;
  }
  if (!gender) {
    alert("Please select your gender.");
    e.preventDefault();
    return;
  }
  if (!dob) {
    alert("Date of Birth is required.");
    form.dob.focus();
    e.preventDefault();
    return;
  }
  if (!email) {
    alert("Email is required.");
    form.email.focus();
    e.preventDefault();
    return;
  }
  if (!/^\S+@\S+\.\S+$/.test(email)) {
    alert("Enter a valid email address.");
    form.email.focus();
    e.preventDefault();
    return;
  }
  if (!phone) {
    alert("Phone number is required.");
    form.phone.focus();
    e.preventDefault();
    return;
  }
  if (!/^[0-9]{10}$/.test(phone)) {
    alert("Phone number must be 10 digits.");
    form.phone.focus();
    e.preventDefault();
    return;
  }
  if (!address) {
    alert("Address is required.");
    form.address.focus();
    e.preventDefault();
    return;
  }
  if (!group) {
    alert("Please select a group.");
    form.groupSelect.focus();
    e.preventDefault();
    return;
  }
  if (!marks) {
    alert("Total Marks are required.");
    form.marks.focus();
    e.preventDefault();
    return;
  }
  if (!course) {
    alert("Please select a course.");
    form.courseSelect.focus();
    e.preventDefault();
    return;
  }
  if (!marksheet) {
    alert("Please upload your 12th marksheet.");
    form.marksheet.focus();
    e.preventDefault();
    return;
  }
  if (marksheet.size > 2 * 1024 * 1024 || marksheet.type !== "application/pdf") {
    alert("Marksheet must be a PDF under 2 MB.");
    form.marksheet.focus();
    e.preventDefault();
    return;
  }
  if (!photo) {
    alert("Please upload your photo.");
    form.photo.focus();
    e.preventDefault();
    return;
  }
  if (
    photo.size > 500 * 1024 ||
    !["image/jpeg", "image/png"].includes(photo.type)
  ) {
    alert("Photo must be JPG/PNG and under 500 KB.");
    form.photo.focus();
    e.preventDefault();
    return;
  }
  if (!acknowledge) {
    alert("Please acknowledge the declaration.");
    document.getElementById("acknowledge").focus();
    e.preventDefault();
    return;
  }

  
});

  window.onload = updateCourses;
</script>
</body>
</html>
