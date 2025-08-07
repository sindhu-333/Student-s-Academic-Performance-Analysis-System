<?php
session_start();
include 'conn.php';  // Assuming conn.php contains your database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the OTP, new password, and confirm password from the form
    $otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if OTP entered matches the one stored in the session
    if (isset($_SESSION['otp']) && $otp == $_SESSION['otp']) {
        // Check if the new password and confirm password match
        if ($new_password === $confirm_password) {
            // Hash the new password for security
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Get the phone number from the session
            $phone_number = $_SESSION['phno'];

            // Use prepared statement to update the password in the database
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE phno = ?");
            $stmt->bind_param("ss", $hashed_password, $phone_number);

            if ($stmt->execute()) {
                // Password successfully reset, redirect to login page or confirmation page
                echo "<script>alert('Your password has been successfully reset. Please log in with your new password.'); window.location.href='log.html';</script>";
                exit();
            } else {
                // If the update query failed
                echo "<script>alert('Something went wrong. Please try again later.'); window.location.href='pass.php';</script>";
                exit();
            }
        } else {
            // Passwords do not match
            echo "<script>alert('Passwords do not match! Please try again.'); window.location.href='pass.php';</script>";
            exit();
        }
    } else {
        // Invalid OTP
        echo "<script>alert('Invalid OTP. Please try again.'); window.location.href='pass.php';</script>";
        exit();
    }
}
?>
