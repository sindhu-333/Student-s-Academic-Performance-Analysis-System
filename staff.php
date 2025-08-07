<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'conn.php';

$classes = [8, 9, 10];
$stats = [];

foreach ($classes as $class) {
    // Total students in this class
    $res1 = $conn->query("SELECT COUNT(*) as count FROM students WHERE class = $class");
    $totalStudents = $res1->fetch_assoc()['count'];

    // Subjects offered in this class
    $res2 = $conn->query("SELECT COUNT(DISTINCT sub_id) as subject_count FROM subjects WHERE class = $class");
    $totalsubjects = $res2->fetch_assoc()['subject_count'];

    // Average percentage in this class
    $res3 = $conn->query("SELECT AVG(percentage) as avg FROM results WHERE class = $class");
    $avgData = $res3->fetch_assoc();
    $avg = $avgData['avg'] !== null ? round($avgData['avg'], 2) : 0;

   

  // Initialize result array


  
    $maleQuery = $conn->prepare("SELECT COUNT(*) as count FROM students WHERE class = ? AND gender = 'male'");
    $maleQuery->bind_param("i", $class);
    $maleQuery->execute();
    $maleResult = $maleQuery->get_result()->fetch_assoc()['count'];

    // Count female students
    $femaleQuery = $conn->prepare("SELECT COUNT(*) as count FROM students WHERE class = ? AND gender = 'female'");
    $femaleQuery->bind_param("i", $class);
    $femaleQuery->execute();
    $femaleResult = $femaleQuery->get_result()->fetch_assoc()['count'];
  
     // Store in result
    $stats["class_$class"] = [
        "totalStudents" => $totalStudents,
        "totalSubjects" => $totalsubjects,
        "averagePercentage" => $avg,
        "male" => (int)$maleResult,
        "female" => (int)$femaleResult
    ];
    

    // Close queries
    $maleQuery->close();
    $femaleQuery->close();

}

// Return as JSON
echo json_encode($stats);
?>
