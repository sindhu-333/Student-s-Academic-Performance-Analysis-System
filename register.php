<?php
session_start();
require_once 'conn.php'; // Include the database connection

// Sanitize input function
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize form inputs
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);
    $confirm_password = sanitizeInput($_POST['confirm_password']);
    $email = sanitizeInput($_POST['email']);
    $phno = sanitizeInput($_POST['phno']);
    $role = sanitizeInput($_POST['role']);
    
    // Ensure hintQn is set, if it's 'other', handle custom hint
    $hintQn = isset($_POST['hintQn']) ? sanitizeInput($_POST['hintQn']) : null;
    $customHint = isset($_POST['customHint']) ? sanitizeInput($_POST['customHint']) : null;
    $hintAns = isset($_POST['hintAns']) ? sanitizeInput($_POST['hintAns']) : null;

    // If the user selects "Other", use the custom hint if provided, else leave it NULL
    if ($hintQn === "other" && empty($customHint)) {
        $hintQn = null;  // You may want to set a default value if the user selects "Other" but doesn't enter a custom hint.
    }

    // Validate password
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $_POST['password'])) {
        die('Password must be at least 8 characters long, include one uppercase letter, one lowercase letter, one number, and one special character.');
    }

    // Validate email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format.');
    }

    // Validate password and confirm password
    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = "Passwords do not match!";
        header("Location: register.html");
        exit();
    }

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the 'users' table
    $stmt = $conn->prepare("INSERT INTO users (username, password, phno, email, role, hintQn, hintAns) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Error preparing SQL query for users table: " . $conn->error);
    }

    // Bind parameters for the users table
    $stmt->bind_param("sssssss", $username, $hashed_password, $phno, $email, $role, $hintQn, $hintAns);
    
    // Execute the query and check for errors
    if ($stmt->execute()) {
        // Get the last inserted user_id
        $id = $stmt->insert_id;

        // Now insert into the respective table based on the role (student or teacher)
        if ($role === 'student') {
            // Insert into the 'students' table, using user_id as the foreign key
            $stmt_student = $conn->prepare("INSERT INTO students (id, username, phone, email) VALUES (?, ?, ?, ?)");
            if (!$stmt_student) {
                die("Error preparing SQL query for student table: " . $conn->error);
            }

            // Bind parameters to insert user_id, phone, and email
            $stmt_student->bind_param("isss", $id, $username, $phno, $email);
            if (!$stmt_student->execute()) {
                die("Error inserting into student table: " . $stmt_student->error);
            }
              // Get the last inserted student_id
            $student_id = $stmt_student->insert_id;

            // Now, insert the student_id and class into the 'marks' table
            $stmt_marks = $conn->prepare("INSERT INTO marks (std_id) VALUES (?)");
            if (!$stmt_marks) {
                die("Error preparing SQL query for marks table: " . $conn->error);
            }

            // Bind parameters for std_id and class
            $stmt_marks->bind_param("i", $student_id);
            if (!$stmt_marks->execute()) {
                die("Error inserting into marks table: " . $stmt_marks->error);
            }

            // Insert into the 'results' table (std_id and class)
            $stmt_results = $conn->prepare("INSERT INTO results (std_id) VALUES (?)");
            if (!$stmt_results) {
                die("Error preparing SQL query for results table: " . $conn->error);
            }

            // Bind parameters for std_id and class
            $stmt_results->bind_param("i", $student_id);
            if (!$stmt_results->execute()) {
                die("Error inserting into results table: " . $stmt_results->error);
            }
             
        } elseif ($role === 'staff') {
            // Insert into the 'staff' table, using user_id as the foreign key
            $stmt_teacher = $conn->prepare("INSERT INTO staff (id, name,  phone, email) VALUES (?, ?, ?, ?)");
            if (!$stmt_teacher) {
                die("Error preparing SQL query for teacher table: " . $conn->error);
            }

            // Bind parameters to insert user_id, phone, and email
            $stmt_teacher->bind_param("isss", $id, $username, $phno, $email);
            if (!$stmt_teacher->execute()) {
                die("Error inserting into teacher table: " . $stmt_teacher->error);
            }
        }elseif ($role === 'admin') {
            // Insert into the 'staff' table, using user_id as the foreign key
            $stmt_admin = $conn->prepare("INSERT INTO admins(id, name,  phone, email) VALUES (?, ?, ?, ?)");
            if (!$stmt_admin) {
                die("Error preparing SQL query for admin table: " . $conn->error);
            }

            // Bind parameters to insert user_id, phone, and email
            $stmt_admin->bind_param("isss", $id, $username, $phno, $email);
            if (!$stmt_admin->execute()) {
                die("Error inserting into admin table: " . $stmt_admin->error);
            }
        }

         

        // Successful registration, redirect to the login page
        $_SESSION['success_message'] = "Registration successful! Please log in.";
        header("Location: log.html");
        exit();
    } else {
        die("Error inserting into users table: " . $stmt->error);
    }
}
?>
