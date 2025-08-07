<?php
// forgot_password.php
session_start();
include 'conn.php';  // Include database connection

// Check if the form is submitted and 'phno' is set in the POST array
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['phno'])) {
    // Sanitize and trim the phone number input
    $phone = trim(mysqli_real_escape_string($conn, $_POST['phno']));

    // Debugging: Check if the phone number is correctly received
    echo "Phone number received: " . $phone . "<br>";

    // Check if phone exists in the database
    $query = "SELECT * FROM users WHERE phno = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Prepared statement failed: " . mysqli_error($conn));
    }

    // Bind the phone number parameter to the query
    $stmt->bind_param("s", $phone);  // "s" means the parameter is a string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Phone number exists in the database, generate OTP
        $otp = rand(100000, 999999);  // Generate a 6-digit OTP
        $_SESSION['otp'] = $otp;  // Store OTP in the session
        $_SESSION['phno'] = $phone;  // Store phone number in the session

        // Close the statement
        $stmt->close();

        // Show OTP in a popup and redirect to the password reset page
        echo "<script>alert('Your OTP is: $otp'); window.location.href='pass_reset.html';</script>";
        exit();
    } else {
        // Debugging: If phone number not found, show the query result
        echo "Phone number not found in the database.<br>";
        echo "Query Result: " . mysqli_num_rows($result) . "<br>";
        
        // If the phone number doesn't exist
        echo "<script>alert('Phone number not found!'); window.location.href='password.html';</script>";
        exit();
    }
} else {
    // If phno is not set in POST request
    echo "Phone number was not provided!";
    exit();
}
?>
