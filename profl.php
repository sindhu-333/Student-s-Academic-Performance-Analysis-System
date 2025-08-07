<?php
session_start();
include 'conn.php';

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // handle update request
    $fullName = $_POST['fullName'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $academicYear = $_POST['academicYear'];  // Assuming this corresponds to the 'year' field
    $parentName = $_POST['parentName'];
    $parentPhone = $_POST['parentPhone'];
    $hintQn = $_POST['hintQn'];
    $customHint = $_POST['customHint'];
    $hintAns = $_POST['hintAns'];
    $class = $_POST['class'];
    $address = $_POST['address'];

    // handle profile image upload
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $imagePath = basename($_FILES['profileImage']['name']);  // Store the image in the same folder as the PHP program
        move_uploaded_file($_FILES['profileImage']['tmp_name'], $imagePath);
        $updateImageSql = ", profile_image = '$imagePath'";
    } else {
        $updateImageSql = "";
    }

    $hintQnValue = ($hintQn === 'other') ? $customHint : $hintQn;

    $sql = "UPDATE students SET 
            full_name='$fullName', gender='$gender', email='$email', dob='$dob',
            year='$academicYear', parent_name='$parentName', parent_phone_number='$parentPhone',
            hint_question='$hintQn', hint_answer='$hintAns', class='$class', address='$address'
            $updateImageSql
            WHERE username='$username'";

    if (mysqli_query($conn, $sql)) {
        echo 'success';
    } else {
        echo 'Database error: ' . mysqli_error($conn);
    }

} else {
    // handle fetch request
    $sql = "SELECT * FROM students WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
}
?>
