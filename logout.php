<?php
// Init a session
session_start();

// Unset all session values
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login
header('location: login.php');
exit;