<?php
// logout.php - User logout
require_once 'config.php';

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Clear remember me cookie if set
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

// Redirect to home page
redirect('index.php');
?>