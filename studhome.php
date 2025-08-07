<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$username = $_SESSION['username'];

// Get student details
$sql = "SELECT std_id, full_name, profile_image, class, gender, dob, phone, email FROM students WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($std_id, $full_name, $profile_image, $class, $gender, $dob, $phone, $email);
$stmt->fetch();
$stmt->close();

if (empty($profile_image)) {
    $profile_image = '';
}

// Get result summary
$sql_result = "SELECT total_marks, percentage, grade, status FROM results WHERE std_id = ?";
$stmt_result = $conn->prepare($sql_result);
$stmt_result->bind_param("i", $std_id);
$stmt_result->execute();
$stmt_result->bind_result($total_marks, $percentage, $grade, $status);
$stmt_result->fetch();
$stmt_result->close();

// Send JSON
echo json_encode([
    'name' => $full_name,
    'profile_image' => $profile_image,
    'class' => $class,
    'gender' => $gender,
    'dob' => $dob,
    'phone' => $phone,
    'email' => $email,
    'summary' => [
        'total_marks' => $total_marks,
        'percentage' => $percentage,
        'grade' => $grade,
        'status' => $status
    ]
]);
?>
