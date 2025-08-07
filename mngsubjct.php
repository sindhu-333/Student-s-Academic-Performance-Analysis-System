<?php 
include 'conn.php';

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'update' && isset($_POST['sub_id'])) {
        $sub_id = intval($_POST['sub_id']);
        $sub_name = $conn->real_escape_string($_POST['sub_name']);
        $max_marks = intval($_POST['max_marks']);
        $passing_marks = intval($_POST['passing_marks']);
        
        if (!empty($sub_id) && !empty($sub_name) && $max_marks > 0 && $passing_marks > 0) {
            $updateSql = "UPDATE subjects SET sub_name = '$sub_name', max_marks = $max_marks, passing_marks = $passing_marks WHERE sub_id = $sub_id";
            if ($conn->query($updateSql) === TRUE) {
                echo "Subject updated successfully!";
            } else {
                echo "Error updating subject: " . $conn->error;
            }
        } else {
            echo "Invalid data!";
        }
    }
} else {
    // Fetch subjects when no action is specified
    $class = isset($_GET['class']) ? intval($_GET['class']) : 0;

    if ($class > 0) {
        $sql = "SELECT sub_id, sub_name, max_marks, passing_marks, class FROM subjects WHERE class = $class";
        $result = $conn->query($sql);
        
        $subjectsData = "";
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $subjectsData .= "
                    <tr id='subject_{$row['sub_id']}'>
                        <td>{$row['sub_id']}</td>
                        <td>{$row['sub_name']}</td>
                        <td>{$row['max_marks']}</td>
                        <td>{$row['passing_marks']}</td>
                        <td>{$row['class']}</td>
                        <td>
                            <button class='action-btn edit-btn' onclick='editSubject({$row['sub_id']})'>Edit</button>
                            <button class='action-btn delete-btn' onclick='deleteSubject({$row['sub_id']})'>Delete</button>
                        </td>
                    </tr>
                ";
            }
        } else {
            $subjectsData = "<tr><td colspan='6' style='text-align:center; color:#aaa;'>No subjects found for this class.</td></tr>";
        }
        
        echo $subjectsData;
    } else {
        echo "<tr><td colspan='6' style='text-align:center; color:#aaa;'>Please select a valid class.</td></tr>";
    }
}

$conn->close();
?>
