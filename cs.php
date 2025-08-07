<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "online_exam";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

Login page:
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Online Examination System</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
   //css styles…
  </style>
</head>
<body>
  <main class="login-container" id="loginSection">
    <h1>Online Examination System</h1>
    <h3><center>Welcome!!</center></h3>
    <div class="role-switch">
      <button type="button" id="adminBtn">Admin Login</button>
      <button type="button" id="staffBtn">Staff Login</button>
      <button type="button" id="studentBtn">Student Login</button>
    </div>

    <form id="loginForm" novalidate>
      <input type="hidden" name="role" id="role" value="admin">
      <div class="form-group">
        <label for="username" id="usernameLabel">Username</label>
        <input type="text" id="username" placeholder="Enter your ID" required>
      </div>
      <div class="form-group password-toggle">
        <label for="password">Password:</label>
        <input type="password" id="password" placeholder="Enter your password" required>
        <span class="password-toggle-icon" onclick="togglePassword()">👁</span>
      </div>
      <div class="form-group">
        <a href="forgot_password.html">Forgot Password? Click here</a>
      </div>
      <div class="form-group" id="schoolIdField">
        <label for="school_id">School ID:</label>
        <input type="text" id="school_id" placeholder="Enter School ID">
      </div>
      <div class="form-group">
        <label>Captcha:</label>
        <div class="captcha-box">
          <div class="captcha-text" id="captchaCode"></div>
          <button type="button" class="refresh-btn" id="refreshCaptcha">Refresh</button>
        </div>
      </div>
      <div class="form-group">
        <label for="captcha_input">Enter Captcha:</label>
        <input type="text" id="captcha_input" placeholder="Enter the captcha shown" required>
      </div>
      <button type="submit" class="login-btn">Login</button>
      <p class="register-link">New candidate? <a href="register.html">Register Here</a></p>
    </form>
  </main>
  <script>
    window.onload = function () {
      const adminBtn = document.getElementById("adminBtn");
      const staffBtn = document.getElementById("staffBtn");
      const studentBtn = document.getElementById("studentBtn");
      const roleInput = document.getElementById("role");
      const schoolIdField = document.getElementById("schoolIdField");
      const usernameLabel = document.getElementById("usernameLabel");
      const captchaCode = document.getElementById("captchaCode");
      const refreshCaptchaBtn = document.getElementById("refreshCaptcha");
      // Role switch logic
      adminBtn.onclick = () => {
        roleInput.value = "admin";
        schoolIdField.style.display = "block";
        usernameLabel.textContent = "Admin Username:";
      };
      staffBtn.onclick = () => {
        roleInput.value = "staff";
        schoolIdField.style.display = "block";
        usernameLabel.textContent = "Staff Username:";
      };
      studentBtn.onclick = () => {
        roleInput.value = "student";
        schoolIdField.style.display = "none";
        usernameLabel.textContent = "Student Username:";
      };
      // Generate Captcha
      function generateCaptcha() {
        const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"
 let code = "";
        for (let i = 0; i < 6; i++) {
          code += chars[Math.floor(Math.random() * chars.length)];
        }
        captchaCode.textContent = code;
      }
      refreshCaptchaBtn.onclick = generateCaptcha;
      generateCaptcha();
      window.togglePassword = function () {
        const passwordInput = document.getElementById("password");
        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
      };
      document.getElementById("loginForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const userCaptcha = document.getElementById("captcha_input").value.trim();
        const actualCaptcha = captchaCode.textContent.trim();
        if (userCaptcha !== actualCaptcha) {
          Swal.fire("Captcha Error", "Captcha does not match!", "error");
          generateCaptcha();
          return;
        }
        const role = document.getElementById("role").value;
        const username = document.getElementById("username").value.trim();
        const password = document.getElementById("password").value.trim();
        const schoolId = document.getElementById("school_id").value.trim();
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "login.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
          if (xhr.status === 200) {
            const response = xhr.responseText.trim();
            if (response === "success") {
              Swal.fire("Login Successful", "Welcome to Online Examination System", "success").then(() => {
                if (role === "admin") {
                  window.location.href = "admin_dashboard.php";
                } else if (role === "staff") {
                  window.location.href = "staff_dashboard.php";
                } else if (role === "student") {
                  window.location.href = "home.php";
                } else {
                  window.location.href = "home.php";
                }
              });
            } else {
              Swal.fire("Login Failed", response, "error");
            }
          } else {
            Swal.fire("Server Error", "Unable to reach the server.", "error");
          }
        };
       xhr.send("role=" + encodeURIComponent(role) +
          "&username=" + encodeURIComponent(username) +
          "&password=" + encodeURIComponent(password) +
          "&school_id=" + encodeURIComponent(schoolId));
      });
    };
  </script>
</body>
</html>

Admin dashboard:
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | Online Exam System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
//css styles
  </style>
</head>
<body>
<!-- Logout Button Top Right -->
<a href="logout1.html" class="btn btn-outline-danger logout-btn">🚪 Logout</a>
<div class="container">
  <!-- Title Centered -->
  <div class="text-center mb-5">
    <h2 class="fw-bold"><b>Admin Dashboard</b></h2>
    <h5 class="t"><b>Manage the entire system seamlessly from this panel.</b></h5>
  </div>
  <div class="row g-4 justify-content-center">
    <!-- Admin Profile -->
    <div class="col-md-4 col-sm-6">
      <div class="card dashboard-card text-center">
        <div class="card-body">
          <div class="dashboard-icon"></div>
          <h5>Admin Profile</h5>
          <p>View your profile info.</p>
          <a href="admin_profile.php" class="btn btn-primary">Go</a>
        </div>
      </div>
    </div>
    <!-- Staff Management -->
    <div class="col-md-4 col-sm-6">
      <div class="card dashboard-card text-center">
        <div class="card-body">
          <div class="dashboard-icon">👥</div>
          <h5>Staff Management</h5>
          <p>View and manage staff presence.</p>
          <a href="manage_staff.php" class="btn btn-success">Manage</a>
        </div>
      </div>
    </div>
    <!-- Student Login Info -->
    <div class="col-md-4 col-sm-6">
      <div class="card dashboard-card text-center">
        <div class="card-body">
          <div class="dashboard-icon">📶</div>
          <h5>Student Management</h5>
          <p>Manage students, Monitor student login activities.</p>
          <a href="manage_students.php" class="btn btn-info">View</a>
        </div>
      </div>
    </div>
    <!-- Student LeaderBoard -->
    <div class="col-md-4 col-sm-6">
      <div class="card dashboard-card text-center">
        <div class="card-body">
          <div class="dashboard-icon">🎓</div>
          <h5>Student LeaderBoard</h5>
          <p>Ranking of the student</p>
          <a href="leaderboard3.php" class="btn btn-warning">View</a>
        </div>
      </div>
    </div>
 <!-- Notice Board -->
    <div class="col-md-4 col-sm-6">
      <div class="card dashboard-card text-center">
        <div class="card-body">
          <div class="dashboard-icon">📢</div>
          <h5>Notice Board</h5>
          <p>Send notices to staff and students.</p>
          <a href="notice_board.php" class="btn btn-dark">Send</a>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-6">
      <div class="card dashboard-card text-center">
        <div class="card-body">
          <div class="dashboard-icon">🎓</div>
          <h5>Exams</h5>
          <p>Staff created exams</p>
          <a href="view_exams1.php" class="btn btn-warning">View</a>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
