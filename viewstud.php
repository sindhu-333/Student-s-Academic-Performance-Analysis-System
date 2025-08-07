<?php
// Include the database connection file
include('conn.php');  // Assuming db.php contains your database connection

// Function to fetch students by class or all students if no class is specified
function getStudents($class = null) {
    global $conn; // Use the existing database connection

    // If a class is provided, fetch students by class
    if ($class) {
        $sql = "SELECT id, full_name, dob, gender, class, year, phone FROM students WHERE class = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $class);
    } else {
        // Otherwise, fetch all students
        $sql = "SELECT id, full_name, dob, gender, class, year, phone FROM students";
        $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    // Start building the table rows
    $studentsHtml = '';

    // Loop through the query result and generate table rows
    while ($row = $result->fetch_assoc()) {
        $studentsHtml .= '<tr>';
        $studentsHtml .= '<td>' . htmlspecialchars($row['id']) . '</td>';
        $studentsHtml .= '<td>' . htmlspecialchars($row['full_name']) . '</td>';
        $studentsHtml .= '<td>' . htmlspecialchars($row['dob']) . '</td>';
        $studentsHtml .= '<td>' . htmlspecialchars($row['gender']) . '</td>';
        $studentsHtml .= '<td>' . htmlspecialchars($row['class']) . '</td>';
        $studentsHtml .= '<td>' . htmlspecialchars($row['year']) . '</td>';
        $studentsHtml .= '<td>' . htmlspecialchars($row['phone']) . '</td>';
        $studentsHtml .= '</tr>';
    }

    return $studentsHtml;
}

// Check if 'class' parameter is passed in the GET request
if (isset($_GET['class'])) {
    $class = $_GET['class']; // Get the selected class value
    $studentsHtml = getStudents($class);  // Fetch students for the selected class
    echo $studentsHtml;
} else {
    // If no class is specified, fetch all students
    $studentsHtml = getStudents();
    echo $studentsHtml;
}
?>