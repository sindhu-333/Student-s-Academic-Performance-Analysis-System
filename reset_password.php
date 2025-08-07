<?php
// reset_password.php
session_start();
include 'conn.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate fields
    if (empty($new_password) || empty($confirm_password)) {
        $message = "Please fill in all required fields.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_password)) {
        $message = "Password must be at least 8 characters long, include one uppercase letter, one lowercase letter, one number, and one special character.";
    } elseif ($new_password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Get the phone number from the session
        $phno = $_SESSION['phno'] ?? '';

        // Make sure phone number exists in the session
        if (empty($phno)) {
            $message = "No user session found.";
        } else {
            // Update the password in the database using the phone number
            $update_query = "UPDATE users SET password=? WHERE phno=?";

            if ($stmt = $conn->prepare($update_query)) {
                $stmt->bind_param('ss', $hashed_password, $phno);

                if ($stmt->execute()) {
                    // Password updated successfully, destroy the session and redirect to login
                    session_destroy();
                    echo "<script>alert('Password reset successful. Please login.'); window.location.href='log.html';</script>";
                    exit();
                } else {
                    $message = "Failed to reset password! Please try again.";
                }

                $stmt->close();
            } else {
                $message = "Error in SQL query: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f3f4f6;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .container {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      width: 350px;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    input[type="password"], input[type="submit"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    input[type="submit"] {
      background-color: #3b82f6;
      color: white;
      border: none;
      cursor: pointer;
    }
    input[type="submit"]:hover {
      background-color: #2563eb;
    }
    .message {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Reset Password</h2>
    <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>
    <form method="post">
      <input type="password" name="new_password" placeholder="New Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <input type="submit" value="Reset Password">
    </form>
  </div>
</body>
</html>