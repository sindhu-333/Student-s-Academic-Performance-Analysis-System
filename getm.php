<?php
session_start();
require_once 'conn.php';

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$username = $_SESSION['username'];

$studentQuery = $conn->query("SELECT std_id FROM students WHERE username = '$username'");
if ($studentQuery && $studentQuery->num_rows > 0) {
    $studentRow = $studentQuery->fetch_assoc();
    $std_id = $studentRow['std_id'];
} else {
    echo json_encode([]);
    exit;
}

$query = "SELECT s.sub_id, s.sub_name, s.max_marks, 
                  m.first_language, m.second_language, m.third_language, 
                  m.maths, m.science, m.social_science, 
                  r.grade, r.status 
           FROM marks m 
           JOIN results r ON m.std_id = r.std_id 
           JOIN subjects s ON s.class = m.class 
           WHERE m.std_id = $std_id";
   
$result = $conn->query($query);
$response = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Assuming 'sub_name' corresponds to the subject name from the 'subjects' table
        $subject_name = strtolower(str_replace(' ', '_', $row['sub_name']));
        
        // Map the correct obtained marks for the subject
        $obtained_marks = isset($row[$subject_name]) ? $row[$subject_name] : 0;

        $response[] = [
            'sub_id' => $row['sub_id'],
            'sub_name' => $row['sub_name'],
            'max_marks' => $row['max_marks'],
            'obtained_marks' => $obtained_marks,  // Correct obtained marks per subject
            'grade' => $row['grade'],
            'status' => $row['status']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();

?>
