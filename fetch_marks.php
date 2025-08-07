<?php
// Database connection
include 'conn.php';

$class = $_GET['class'];  // Get class from the GET request

// Fetch marks based on the selected class
$sql = "SELECT marks.std_id, marks.first_language, marks.second_language, marks.third_language, marks.maths, marks.science, marks.social_science, marks.max_marks,
               (marks.first_language + marks.second_language + marks.third_language + marks.maths + marks.science + marks.social_science) AS total_marks
        FROM marks
        WHERE marks.class = '$class'";

$result = $conn->query($sql);
$marks = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $marks[] = $row;
    }
    echo json_encode($marks);  // Return data as JSON
} else {
    echo '';  // No data found
}

$conn->close();
?>
