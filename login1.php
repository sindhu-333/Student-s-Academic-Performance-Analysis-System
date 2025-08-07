<?php
session_start();
require_once 'conn.php';

// Sanitize input function
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Limit login attempts
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if ($_SESSION['login_attempts'] >= 5) {
    die("Too many login attempts. Please try again later.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    $enteredSchoolId = isset($_POST['school_id']) ? sanitizeInput($_POST['school_id']) : '9998';

    if (empty($username) || empty($password)) {
        $_SESSION['login_attempts']++;
        die("Username and password are required.");
    }

    // Fetch user by username only (not by role)
    $sql = "SELECT id, username, password, role, school_id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("SQL query preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();

    if ($stmt->errno) {
        die("Error executing query: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        $userRole = strtolower($user['role']);

        // Check school_id if role is staff or admin
        $actualSchoolId = 208852; // Hardcoded school ID
        if (($userRole === "admin" || $userRole === "staff") && intval($enteredSchoolId) !== $actualSchoolId) {
            $_SESSION['login_attempts']++;
            die("<script> alert('Invalid or mismatched school ID'),location.href='log.html'; </script>");
        }

        // Check password
        if (password_verify($password, $user['password'])) {
            $_SESSION['login_attempts'] = 0;

            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['school_id'] = $user['school_id'];

            // Redirect based on role from DB with alert message
            if ($userRole === 'admin') {
                echo "<script>alert('Login successful! Welcome Admin.'); window.location.href='admin.html';</script>";
            } elseif ($userRole === 'staff') {
                echo "<script>alert('Login successful! Welcome Staff.'); window.location.href='staff.html';</script>";
            } else {
                echo "<script>alert('Login successful! Welcome Student.'); window.location.href='stddash.html';</script>";
            }
            exit;
        } else {
            $_SESSION['login_attempts']++;
            die("<script> alert('Invalid password'),location.href='log.html'; </script>");;
        }
    } else {
        die("<script> alert('User not found or invalid role...'),location.href='log.html'; </script>");
    }
} else {
    die("<script> alert('Unauthorised access.'),location.href='log.html'; </script>");
}
?>
