<?php
// Log PHP errors to a file instead of sending to browser (important for AJAX)
ini_set('log_errors', 1);
ini_set('error_log', 'php_error.log'); // check this file after testing
ini_set('display_errors', 0); // do NOT show in browser
error_reporting(E_ALL);

header('Content-Type: application/json');

include 'conn.php'; // Make sure this file has NO extra output

$class = $_POST['class'] ?? '';
$option = $_POST['option'] ?? '';
$data = [];

if (!$class || !$option) {
    echo json_encode(['error' => 'Invalid input']);
    exit;
}



// continue your logic..

$sql = "
    SELECT r.percentage, r.grade, r.status
    FROM results r
    JOIN students s ON r.std_id = s.std_id
    WHERE s.class = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $class);
$stmt->execute();
$result = $stmt->get_result();

$rawData = $result->fetch_all(MYSQLI_ASSOC);

// Process data based on the option
if ($option === 'percentage') {
    $ranges = [
        '90+' => 0,
        '80-89' => 0,
        '60-79' => 0,
        '40-59' => 0,
        '<40' => 0
    ];

    foreach ($rawData as $row) {
        $p = $row['percentage'];
        if ($p >= 90) $ranges['90+']++;
        elseif ($p >= 80) $ranges['80-89']++;
        elseif ($p >= 60) $ranges['60-79']++;
        elseif ($p >= 40) $ranges['40-59']++;
        else $ranges['<40']++;
    }

    foreach ($ranges as $range => $count) {
        $data[] = ['range' => $range, 'count' => $count];
    }

} elseif ($option === 'grade') {
    // Define all possible grades
    $grades = [
        'A+' => 0,
        'A' => 0,
        'B+' => 0,
        'B' => 0,
        'NC' => 0
    ];

    foreach ($rawData as $row) {
        $g = $row['grade'];
        if ($g == 'A+') $grades['A+']++;
        elseif ($g =='A') $grades['A']++;
        elseif ($g == 'B+') $grades['B+']++;
        elseif ($g == 'B') $grades['B']++;
        else $grades['NC']++;
    }

    foreach ($grades as $grade => $count) {
        $data[] = ['grade' => $grade, 'count' => $count];
    }

}  elseif ($option === 'status') {
    $statusData = ['Passed' => 0, 'Failed' => 0];

    foreach ($rawData as $row) {
        $s = strtolower(trim($row['status']));
        if ($s === 'pass' || $s === 'Passed') {
            $statusData['Passed']++;
        } else {
            $statusData['Failed']++;
        }
    }

    foreach ($statusData as $status => $count) {
        $data[] = ['status' => $status, 'count' => $count];
    }
}


echo json_encode($data);
