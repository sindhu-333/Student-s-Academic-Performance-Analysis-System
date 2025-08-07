<?php
include 'conn.php';

// Promote students to next class (POST: promote=1)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['promote'])) {
    // Delete all students in class 10 (they have completed school)
    $delete10 = $conn->query("DELETE FROM students WHERE class = 10");
    // Promote students in class 8 and 9 (not 10)
    $promote = $conn->query("UPDATE students SET class = class + 1 WHERE class IN (8,9)");
    if ($promote) {
        echo "Promotion successful. Class 10 students removed.";
    } else {
        echo "Promotion failed.";
    }
    $conn->close();
    exit();
}

// DELETE student by POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['std_id'])) {
    $id = intval($_POST['std_id']);
    $query = "DELETE FROM students WHERE std_id = $id";
    if ($conn->query($query)) {
        echo "Success";
    } else {
        echo "Error";
    }
    $conn->close();
    exit();
}

// FETCH students by class (GET)
if (isset($_GET['class'])) {
    $class = intval($_GET['class']);
    $query = "SELECT std_id, full_name, gender, phone, email, class FROM students WHERE class = $class";
    $result = $conn->query($query);

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    echo json_encode($students);
}

$conn->close();
?>
