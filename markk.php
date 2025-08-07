<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$username = $_SESSION['username'];

// Get student details
$sql = "SELECT std_id, class FROM students WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($std_id, $class);
$stmt->fetch();
$stmt->close();

// Get marks
$sql = "SELECT first_language, second_language, third_language, maths, science, social_science, total_marks FROM marks WHERE std_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $std_id);
$stmt->execute();
$stmt->bind_result($first_language, $second_language, $third_language, $maths, $science, $social_science, $total_marks);
$stmt->fetch();
$stmt->close();

// Get subjects and passing marks
$subjects = [];
$sql = "SELECT sub_name, passing_marks FROM subjects WHERE class = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $class);
$stmt->execute();
$stmt->bind_result($sub_name, $passing_marks);
while ($stmt->fetch()) {
    $subjects[] = ['name' => $sub_name, 'passing_marks' => $passing_marks];
}
$stmt->close();

// Get overall result
$sql = "SELECT percentage, grade, status FROM results WHERE std_id = ? AND class = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $std_id, $class);
$stmt->execute();
$stmt->bind_result($percentage, $grade, $status);
$stmt->fetch();
$stmt->close();

// Build response
$response = [
    'marks' => [
        'first_language' => $first_language,
        'second_language' => $second_language,
        'third_language' => $third_language,
        'maths' => $maths,
        'science' => $science,
        'social_science' => $social_science
    ],
    'subjects' => $subjects,
    'summary' => [
        'total_marks' => $total_marks,
        'percentage' => $percentage,
        'grade' => $grade,
        'status' => $status
    ]
];

echo json_encode($response);
?>
