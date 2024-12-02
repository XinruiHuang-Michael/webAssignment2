<?php
// start session
session_start();

// clean the seesion
session_unset();

// destroy the seesion
session_destroy();

// redirect to admin log in page
header("Location: ../pages/admin_login.php"); 
exit;
?>