<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_analysis";  // Change this to your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$std_id = $_POST['std_id'];

$sql = "DELETE FROM marks WHERE std_id = '$std_id'";

if ($conn->query($sql) === TRUE) {
    echo "success";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
