<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if already subscribed
        $check = mysqli_query($conn, "SELECT id FROM newsletter_subscribers WHERE email = '$email'");
        
        if (mysqli_num_rows($check) == 0) {
            mysqli_query($conn, "INSERT INTO newsletter_subscribers (email) VALUES ('$email')");
            $_SESSION['message'] = "Successfully subscribed to newsletter!";
        } else {
            $_SESSION['message'] = "Email already subscribed.";
        }
    } else {
        $_SESSION['message'] = "Invalid email address.";
    }
    
    // Redirect back to the page they came from
    $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    header("Location: $referer");
    exit();
}
?>