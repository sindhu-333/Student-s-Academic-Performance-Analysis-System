<?php
include 'conn.php';
// Calculate percentage and grade based on total_marks
$percentage = ($total_marks / 600) * 100; // Assuming total marks are out of 600
if ($percentage >= 90) {
    $grade = 'A+';
    $status = 'Passed';
} elseif ($percentage >= 75) {
    $grade = 'A';
    $status = 'Passed';
} elseif ($percentage >= 50) {
    $grade = 'B+';
    $status = 'Passed';
}elseif ($percentage >= 40) { 
    $grade = 'B';
    $status = 'Passed';
} else {
    $grade = 'NC';
    $status = 'Failed';
}

// Insert or update results in the `results` table
$sql_check_result = "SELECT id FROM results WHERE std_id = $std_id AND class = $class";
$result_check_result = $conn->query($sql_check_result);

if ($result_check_result->num_rows > 0) {
    // Update existing result
    $sql_update_result = "UPDATE results SET max_marks = $max_marks, total_marks = $total_marks, percentage = $percentage,
                          grade = '$grade', status = '$status' WHERE std_id = $std_id AND class = $class";
    if ($conn->query($sql_update_result) === TRUE) {
        echo "Result updated successfully.";
    } else {
        echo "Error updating result: " . $conn->error;
    }
} else {
    // Insert new result
    $sql_insert_result = "INSERT INTO results (std_id, class, max_marks, total_marks, percentage, grade, status)
                          VALUES ($std_id, $class, $max_marks, $total_marks, $percentage, '$grade', '$status')";
    if ($conn->query($sql_insert_result) === TRUE) {
        echo "Result inserted successfully.";
    } else {
        echo "Error inserting result: " . $conn->error;
    }
}

$conn->close();
?>
