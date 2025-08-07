<?php
include 'conn.php';
 

$action = isset($_GET['action']) ? $_GET['action'] : '';


if ($action == 'studentCount') {
  $sql = "SELECT COUNT(*) AS count FROM students";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  // Return plain text count
  echo $row['count'];
}

else if ($action == 'staffCount') {
  $sql = "SELECT COUNT(*) AS count FROM staff";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  // Return plain text count
  echo $row['count'];
}

else if ($action == 'genderDistribution') {
  $sqlBoys = "SELECT COUNT(*) AS count FROM students WHERE gender='Male'";
  $sqlGirls = "SELECT COUNT(*) AS count FROM students WHERE gender='Female'";
  
  $boysResult = mysqli_query($conn, $sqlBoys);
  $girlsResult = mysqli_query($conn, $sqlGirls);

  $boys = mysqli_fetch_assoc($boysResult)['count'];
  $girls = mysqli_fetch_assoc($girlsResult)['count'];

  header('Content-Type: application/json'); // ✅ Return JSON
  echo json_encode(['boys' => $boys, 'girls' => $girls]);
  exit;
}


else if ($action === 'staffList') {

  $query = "SELECT staff_id, name, email FROM staff";
  $result = mysqli_query($conn, $query);

  $staffList = [];

  while ($row = mysqli_fetch_assoc($result)) {
      $staffList[] = $row;
  }

  header('Content-Type: application/json'); // Important!
  echo json_encode($staffList);
  exit;
}
else {
  echo "Invalid action";
}

mysqli_close($conn);
?>
