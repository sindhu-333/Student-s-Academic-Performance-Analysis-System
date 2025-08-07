<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
include 'conn.php';

// Get data from POST request
$std_id = $_POST['std_id'] ?? null;
$first_language = $_POST['first_language'] ?? 0;
$second_language = $_POST['second_language'] ?? 0;
$third_language = $_POST['third_language'] ?? 0;
$maths = $_POST['maths'] ?? 0;
$science = $_POST['science'] ?? 0;
$social_science = $_POST['social_science'] ?? 0;
$max_marks = $_POST['max_marks'] ?? 0;

if ($std_id === null) {
    die("Error: Student ID not provided.");
}

// Calculate total marks
$total_marks = $first_language + $second_language + $third_language + $maths + $science + $social_science;

// Validate total marks against max marks
if ($total_marks > $max_marks) {
    die("Error: Total marks ($total_marks) cannot exceed maximum marks ($max_marks). Please check the input values.");
}

// Debugging
error_log("Received: std_id=$std_id, total=$total_marks, max=$max_marks");

// Update the marks table
$sql = "UPDATE marks SET 
    first_language = '$first_language', 
    second_language = '$second_language', 
    third_language = '$third_language', 
    maths = '$maths', 
    science = '$science', 
    social_science = '$social_science', 
    total_marks = '$total_marks', 
    max_marks = '$max_marks'
    WHERE std_id = '$std_id'";

if ($conn->query($sql) === TRUE) {

    // Calculate percentage
    $percentage = ($max_marks > 0) ? ($total_marks / $max_marks) * 100 : 0;

    // Determine grade
    if ($percentage >= 90) {
        $grade = 'A+';
    } elseif ($percentage >= 80) {
        $grade = 'A';
    } elseif ($percentage >= 70) {
        $grade = 'B+';
    } elseif ($percentage >= 60) {
        $grade = 'B';
    } elseif ($percentage >= 40) {
        $grade = 'C';
    } else {
        $grade = 'NC';
    }

    // Determine status
    $status = ($percentage >= 40) ? 'Pass' : 'Fail';

    // Get class of student from students table
    $class_query = "SELECT class FROM students WHERE std_id = '$std_id'";
    $class_result = $conn->query($class_query);
    if ($class_result && $class_result->num_rows > 0) {
        $row = $class_result->fetch_assoc();
        $class = $row['class'];
    } else {
        die("Error: Class not found for student ID $std_id");
    }

    // Update or insert into results table
    $update_results = "INSERT INTO results (std_id, class, max_marks, total_marks, percentage, grade, status)
        VALUES ('$std_id', '$class', '$max_marks', '$total_marks', '$percentage', '$grade', '$status')
        ON DUPLICATE KEY UPDATE
            max_marks = VALUES(max_marks),
            total_marks = VALUES(total_marks),
            percentage = VALUES(percentage),
            grade = VALUES(grade),
            status = VALUES(status)";

    if ($conn->query($update_results) === TRUE) {
        echo 'success';
    } else {
        echo 'error updating results: ' . $conn->error;
    }

} else {
    echo 'error updating marks: ' . $conn->error;
}

$conn->close();
?>
