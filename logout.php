<?php
session_start(); // Start the session

// Destroy all session variables to log the user out
session_unset();
session_destroy();

// Redirect to the homepage (index.php)
header("Location: index.php");
exit();
?>
