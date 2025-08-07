<?php
include 'conn.php';

// Get class ID from query string (default to 0 if not provided)
$classId = isset($_GET['class']) ? (int)$_GET['class'] : 0;

if ($classId > 0) {
    // Prepare and execute the query to fetch subjects for the given class
    $sql = "SELECT sub_id, sub_name, max_marks, passing_marks, class, staff_id FROM subjects WHERE class = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $classId);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // If no class selected, fetch all subjects
    $sql = "SELECT sub_id, sub_name, max_marks, passing_marks, class,staff_id FROM subjects";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
}

// Check if subjects exist
if ($result->num_rows > 0) {
    // Output the subjects in a table format
    echo '<table id="subject-table-content" style="display: table;">
            <thead>
                <tr>
                    <th>Subject ID</th>
                    <th>Subject Name</th>
                    <th>Max Marks</th>
                    <th>Passing Marks</th>
                    <th>Class</th>
                    <th>Teacher ID</th>
                </tr>
            </thead>
            <tbody>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['sub_id'] . '</td>';
        echo '<td>' . $row['sub_name'] . '</td>';
        echo '<td>' . $row['max_marks'] . '</td>';
        echo '<td>' . $row['passing_marks'] . '</td>';
        echo '<td>' . $row['class'] . '</td>';
        echo '<td>' . $row['staff_id'] . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
} else {
    // No subjects available
    echo '<div class="no-subjects">No subjects available.</div>';
}

// Close the connection
$conn->close();
?>
