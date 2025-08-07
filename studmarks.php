<?php
session_start();
include 'conn.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$username = $_SESSION['username'];

// Get std_id from students table
$sql = "SELECT std_id FROM students WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    echo json_encode(['error' => 'Student not found']);
    exit;
}

$std_id = $row['std_id'];
$response = [];

// Get marks details
$markSql = "SELECT first_language, second_language, third_language, maths, science, social_science, max_marks, total_marks FROM marks WHERE std_id = ?";
$markStmt = $conn->prepare($markSql);
$markStmt->bind_param("i", $std_id);
$markStmt->execute();
$markResult = $markStmt->get_result();

if ($markRow = $markResult->fetch_assoc()) {
    $response['marks'] = $markRow;
} else {
    $response['marks'] = null;
}

// Get summary details
$summarySql = "SELECT max_marks, total_marks, percentage, grade, status FROM results WHERE std_id = ?";
$summaryStmt = $conn->prepare($summarySql);
$summaryStmt->bind_param("i", $std_id);
$summaryStmt->execute();
$summaryResult = $summaryStmt->get_result();

if ($summaryRow = $summaryResult->fetch_assoc()) {
    $response['summary'] = $summaryRow;
} else {
    $response['summary'] = null;
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
