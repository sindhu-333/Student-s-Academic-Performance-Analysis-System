<?php
// verify_hint.php
session_start();
require_once 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['action']) && $_GET['action'] === 'getHintQuestion') {
    header('Content-Type: application/json'); // Ensure JSON response

    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['username'] ?? '';

    if (empty($username)) {
        echo json_encode(['error' => 'Username is required']);
        exit;
    }

    $sql = "SELECT hintQn FROM users WHERE username = ? LIMIT 1";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($hintQn);

        if ($stmt->fetch()) {
            echo json_encode(['success' => true, 'hintQn' => $hintQn]);
        } else {
            echo json_encode(['error' => 'Username not found']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Database query error']);
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $hint_question = trim($_POST['hintQn']);
    $hint_answer = trim($_POST['hintAns']);

    if (empty($username) || empty($hint_question) || empty($hint_answer)) {
        echo "<script>alert('Please fill in all the required fields.'); window.location.href = 'forgot_password.html';</script>";
        exit;
    }

    // Updated SQL query to handle case sensitivity for hintQn and hintAns
    $sql = "SELECT id, phno FROM users WHERE username = ? AND LOWER(hintQn) = LOWER(?) AND LOWER(hintAns) = LOWER(?) LIMIT 1";

    if ($stmt = $conn->prepare($sql)) {
        // Fix for "Only variables should be passed by reference"
        $lower_hint_question = strtolower($hint_question);
        $lower_hint_answer = strtolower($hint_answer);
        $stmt->bind_param("sss", $username, $lower_hint_question, $lower_hint_answer);
        $stmt->execute();
        $stmt->store_result();

        // Ensure the `phno` session variable is set correctly
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $phno);
            $stmt->fetch();

            $_SESSION['id'] = $id;
            $_SESSION['phno'] = $phno; // Set the phone number in the session

            header("Location: reset_password.php");
            exit();
        } else {
            //echo "<script>alert('Incorrect username, hint question, or answer. Please try again.'); window.location.href = 'forgot_password.html';</script>";
        }

        $stmt->close();
    } else {
        echo "Error in SQL query: " . $conn->error;
    }
}

// Add error handling for unexpected issues
try {
    $action = $_GET['action'] ?? null; // Fix for "Undefined array key 'action'"

    // Debugging log to check the action parameter
    error_log("Action parameter: " . $action);

    if ($action !== 'getHintQuestion') {
        echo json_encode(['error' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
}
?>
