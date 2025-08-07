<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Check if both class and subject are selected
    if (isset($_POST['class']) && isset($_POST['subject'])) {
        $class = $_POST['class'];
        $subject = $_POST['subject'];

        // Define the subject column based on the input subject
        $subject_column = '';
        switch($subject) {
            case 'First language':
                $subject_column = 'first_language';
                break;
            case 'Second language':
                $subject_column = 'second_language';
                break;
            case 'Third language':
                $subject_column = 'third_language';
                break;
            case 'Maths':
                $subject_column = 'maths';
                break;
            case 'Science':
                $subject_column = 'science';
                break;
            case 'Social Science':
                $subject_column = 'social_science';
                break;
            default:
                echo "Invalid subject selected.";
                exit;
        }

        // ✅ Fetch max_marks from the subjects table
        $maxQuery = "SELECT max_marks FROM subjects WHERE sub_name = '$subject' AND class = '$class'";
        $maxResult = mysqli_query($conn, $maxQuery);

        if ($maxRow = mysqli_fetch_assoc($maxResult)) {
            $max_marks = $maxRow['max_marks'];
        } else {
            $max_marks = 'N/A'; // fallback if subject not found
        }

        // If a student ID is provided, search for that student only
        if (isset($_POST['std_id']) && !empty($_POST['std_id'])) {
            $student_id = $_POST['std_id'];

            // Query to fetch specific student result for the selected class, subject, and student ID
            $sql = "SELECT s.std_id, s.class, m.$subject_column AS obtained_marks, r.status
                    FROM students s
                    JOIN marks m ON s.std_id = m.std_id
                    JOIN results r ON s.std_id = r.std_id
                    WHERE s.class = '$class' AND s.std_id = '$student_id'";

        } else {
            // Query to fetch all students' results for the selected class and subject
            $sql = "SELECT s.std_id, s.class, m.$subject_column AS obtained_marks,  r.status
                    FROM students s
                    JOIN marks m ON s.std_id = m.std_id
                    JOIN results r ON s.std_id = r.std_id
                    WHERE s.class = '$class'";
        }

        // Execute the query
        $result = mysqli_query($conn, $sql);

        // Check if rows are returned and output the results
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $std_id = $row['std_id'];
                $class = $row['class'];
                $obtained_marks = $row['obtained_marks'];
                //$total_marks = $row['total_marks'];
                $status = $row['status'];

                echo "<tr>
                        <td>$std_id</td>
                        <td>$class</td>
                        <td>$subject</td>
                        <td>$max_marks</td>
                        <td>$obtained_marks</td>
                        <td>$status</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No results found for this class and subject.</td></tr>";
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>
