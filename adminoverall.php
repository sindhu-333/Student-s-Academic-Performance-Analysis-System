<?php
include 'conn.php';
$class = isset($_GET['class']) ? intval($_GET['class']) : 0;

$response = [
    'students' => [],
    'passedBoys' => 0, 'passedGirls' => 0,
    'failedBoys' => 0, 'failedGirls' => 0,
    'overallPercentage' => 0
];

if ($class) {
    $stmt = $conn->prepare("SELECT s.std_id, s.full_name, s.gender, r.class, r.percentage, r.status, r.total_marks, r.max_marks FROM students s JOIN results r ON s.std_id = r.std_id WHERE r.class = ?");
    $stmt->bind_param("i", $class);
    $stmt->execute();
    $result = $stmt->get_result();

    $totalMarks = $maxMarks = 0;

    while ($row = $result->fetch_assoc()) {
        $response['students'][] = [
            'std_id' => $row['std_id'],
            'full_name' => $row['full_name'],
            'class' => $row['class'],
            'percentage' => $row['percentage']
        ];

        if ($row['status'] === 'Pass') {
            if ($row['gender'] === 'Male') $response['passedBoys']++;
            else $response['passedGirls']++;
        } else {
            if ($row['gender'] === 'Male') $response['failedBoys']++;
            else $response['failedGirls']++;
        }

        $totalMarks += $row['total_marks'];
        $maxMarks += $row['max_marks'];
    }

    if ($maxMarks > 0) {
        $response['overallPercentage'] = round(($totalMarks / $maxMarks) * 100, 2);
    }

    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>
