<?php
include 'conn.php';

$max_marks = 600; // Adjust this if needed

function calculateGrade($percentage) {
    if ($percentage >= 90) return 'A+';
    elseif ($percentage >= 80) return 'A';
    elseif ($percentage >= 70) return 'B+';
    elseif ($percentage >= 60) return 'B';
    elseif ($percentage >= 40) return 'C';
    else return 'NC';
}

function getStatus($percentage) {
    return ($percentage >= 40) ? 'Passed' : 'Failed';
}

// 1. Search by Class
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['class'])) {
    $class = $_POST['class'];
    $sql = "SELECT std_id, class, max_marks, total_marks FROM results WHERE class = '$class'";
}
// 2. Search by Student ID
elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    $sql = "SELECT std_id, class, max_marks, total_marks FROM results WHERE std_id = '$student_id'";
} else {
    echo "<tr><td colspan='6'>Invalid Request</td></tr>";
    exit;
}

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $student_id = $row['std_id'];
        $class = $row['class'];
        $max_marks = $row['max_marks'];
        $total = $row['total_marks'];
        $percentage = ($total / $max_marks) * 100;
        $grade = calculateGrade($percentage);
        $status = getStatus($percentage);

        echo "<tr>
                <td>$student_id</td>
                <td>$class</td>
                <td>$max_marks</td>
                <td>$total</td>
                <td>" . round($percentage, 2) . "%</td>
                <td>$grade</td>
                <td>$status</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No results found.</td></tr>";
}

mysqli_close($conn);
?>
