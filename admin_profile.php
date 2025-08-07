<?php
session_start();
include 'conn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Please login first.']);
    exit();
}

$username = $_SESSION['username'];

// Get user id
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id);
if (!$stmt->fetch()) {
    echo json_encode(['error' => 'User not found.']);
    exit();
}
$stmt->close();

// If POST request → update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';

    $stmt = $conn->prepare("UPDATE admins SET name=?, email=?, phone=?, address=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'fail', 'error' => $stmt->error]);
    }
    $stmt->close();
    $conn->close();
    exit();
}

// If GET request → return profile data
$stmt = $conn->prepare("SELECT name, email, phone, address FROM admins WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $phone, $address);
if ($stmt->fetch()) {
    echo json_encode([
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'address' => $address
    ]);
} else {
    echo json_encode(['error' => 'Admin not found.']);
}
$stmt->close();
$conn->close();
?>
