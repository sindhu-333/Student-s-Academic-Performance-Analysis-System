<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

include 'conn.php';

if (!isset($conn)) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// ✅ Delete Staff if ID is sent
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $conn->query("DELETE FROM subjects WHERE staff_id = '$id'");
    $conn->query("DELETE FROM staff WHERE staff_id = '$id'");
    $conn->query("DELETE FROM users WHERE id = '$id'");
    echo json_encode(["message" => "Staff deleted successfully"]);
    exit;
}

// Handle update (edit) staff data via POST (from mngstaff.html fetch)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid input"]);
        exit;
    }
    $staffId = $conn->real_escape_string($input['staffId'] ?? '');
    $name = $conn->real_escape_string($input['name'] ?? '');
    $phone = $conn->real_escape_string($input['phone'] ?? '');
    $email = $conn->real_escape_string($input['email'] ?? '');
    if (!$staffId || !$name || !$phone || !$email) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }
    $update = $conn->prepare("UPDATE staff SET name=?, phone=?, email=? WHERE staff_id=?");
    $update->bind_param("ssss", $name, $phone, $email, $staffId);
    if ($update->execute()) {
        echo "success";
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to update staff"]);
    }
    $update->close();
    exit;
}

// ✅ Fetch each staff with one subject
$sql = "SELECT s.staff_id, s.name, s.gender, s.phone, s.email, sub.sub_name
        FROM staff s
        LEFT JOIN (
            SELECT staff_id, sub_name
            FROM subjects
            GROUP BY staff_id
        ) AS sub ON s.staff_id = sub.staff_id";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Query failed: " . $mysqli->error]);
    exit;
}

$staffList = [];

while ($row = $result->fetch_assoc()) {
    $staffList[] = [
        "staff_id" => $row['staff_id'],
        "name" => $row['name'],
        "gender" => $row['gender'],
        "phone" => $row['phone'],
        "email" => $row['email'],
        "subject" => $row['sub_name'] ?: "N/A"
    ];
}

echo json_encode($staffList);
?>
