<?php
session_start();

// Reset the login attempts counter
unset($_SESSION['login_attempts']);

// Redirect back to the login page
header("Location: login.php");
exit;
?>
