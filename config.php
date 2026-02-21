<?php
// config.php - Database configuration file
// Save this in C:\xampp\htdocs\NGO\config.php

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Leave empty for XAMPP default
define('DB_NAME', 'ngo_db');

// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to sanitize input data
function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

// Function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to get current user data
function getCurrentUser() {
    global $conn;
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        $query = "SELECT * FROM users WHERE id = $user_id";
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result);
    }
    return null;
}

// Function to display error messages
function displayError($message) {
    return '<div class="alert alert-error" style="background-color: #f44336; color: white; padding: 15px; margin: 10px 0; border-radius: 5px;">' . $message . '</div>';
}

// Function to display success messages
function displaySuccess($message) {
    return '<div class="alert alert-success" style="background-color: #4CAF50; color: white; padding: 15px; margin: 10px 0; border-radius: 5px;">' . $message . '</div>';
}

// Function to format date
function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

// Function to get total funds
function getTotalFunds() {
    global $conn;
    $query = "SELECT total_funds FROM funds_tracking WHERE id = 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total_funds'] ?? 0;
}

// Function to get user's total volunteer hours
function getUserVolunteerHours($user_id) {
    global $conn;
    $query = "SELECT SUM(hours) as total FROM volunteer_hours WHERE user_id = $user_id AND status = 'approved'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}
?>