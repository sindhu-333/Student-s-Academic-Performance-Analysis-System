<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB connection (adjust with your own DB credentials)
include 'conn.php';

$class = $_POST['class'];

// Check if class is empty
if (empty($class)) {
    echo json_encode(["error" => "Class is required"]);
    exit;
}

// Prepare the SQL query to fetch focus required students
$sql = "
    SELECT r.std_id, r.class, r.total_marks, r.percentage, r.status, r.max_marks
    FROM results r
    WHERE r.percentage < 40 AND r.class = ?
";

$stmt = $conn->prepare($sql);

// Check for errors in prepared statement
if (!$stmt) {
    echo json_encode(["error" => "Failed to prepare query"]);
    exit;
}

// Bind the class parameter to the query
$stmt->bind_param("s", $class);
$stmt->execute();

$result = $stmt->get_result();

$focus_students = [];

// Fetch the data and populate the array
while ($row = $result->fetch_assoc()) {
    $focus_students[] = [
        'student id' => $row['std_id'],
        'class' => $row['class'],
        'max marks' => $row['max_marks'],
        'obtained marks' => $row['total_marks'],  // "obtained marks"
        'percentage' => $row['percentage'],
        'status' => $row['status']
    ];
}

// If no data is found
if (empty($focus_students)) {
    echo json_encode(["message" => "No students found below 40%"]);
    exit;
}

// Return JSON encoded data
header('Content-Type: application/json');
echo json_encode($focus_students);

// Close database connection
$conn->close();
?>
