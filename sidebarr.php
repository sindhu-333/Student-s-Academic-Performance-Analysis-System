<html> 
<head>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
}

.header {
    width: 100%;
    
    background-color: #303032;
    color: #faf7f7;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding: 10px 20px;
    box-sizing: border-box;
}

.header .home-icon {
    cursor: pointer;
    color: #faf7f7;
    font-size: 28px;
    transition: color 0.3s;
}

.header .home-icon:hover {
    color: #50dd0a;
}

.sidebar {
    width: 250px;
    
    background-color: #303032;
    color: #faf7f7;
    height:92vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 20px;
}

.sidebar img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-bottom: 10px;
}

.sidebar h2 {
    margin: 10px 0 5px;
    font-size: 18px;
}

.sidebar .profile p {
    margin: 5px 0 15px;
    font-size: 16px;
    font-weight: bold;
}

.sidebar .nav-menu {
    display: flex;
    flex-direction: column;
    width: 100%;
    align-items: center;
}

.sidebar .nav-menu a,
.sidebar .logout-btn {
    padding: 12px 20px;
    text-align: left;
    text-decoration: none;
    color: #fff;
    width: 80%;
    border: none;
    background: none;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.3s, transform 0.3s;
    border-radius: 8px;
    display: flex;
    align-items: center;
}

.sidebar .nav-menu a:hover,
.sidebar .logout-btn:hover {
    background-color: #50dd0a;
    color: #333;
    transform: translateX(8px);
}

.sidebar .nav-menu a i,
.sidebar .logout-btn i {
    margin-right: 10px;
    vertical-align: middle;
}
</style>
</head>
<body>


<?php
session_start();
include 'conn.php';

$username = $_SESSION['username'];

$query = $conn->prepare("SELECT full_name, profile_image FROM students WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$query->bind_result($full_name, $profile_image);
$query->fetch();
$query->close();
$conn->close();
?>
<div class="sidebar">
    
    <div class="profile">
        <img src="<?php echo $profile_image; ?>" alt="Profile Picture">
        <p>Welcome, <?php echo htmlspecialchars($full_name); ?>!</p>
    </div>
    <div class="nav-menu">
        <a href="profile.html"><i class="material-icons">person</i> Profile</a>
        <a href="viewmrk.html"><i class="material-icons">info</i> View Marks</a>
        <!--<a href="view_result.html"><i class="material-icons">assignment</i> View Result</a>
        <a href="view_attendance.html"><i class="material-icons">event_available</i> View Attendance</a>-->
        <button class="logout-btn" onclick="logout()"><i class="material-icons">logout</i> Logout</button>
    </div>
</div>

<script>
function logout() {
    window.location.href = 'logout.php';
}
</script>
</body>
</html>
