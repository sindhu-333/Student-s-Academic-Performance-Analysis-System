<?php
session_start();  // Start the session

// Destroy the session when the user logs out
session_unset();  // Unset all session variables
session_destroy();  // Destroy the session itself

// Redirect to the login page
header("Location: log.html");
exit();
?>
