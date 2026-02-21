<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);
    
    // Validate
    $errors = [];
    if (empty($name)) $errors[] = "Name is required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (empty($message)) $errors[] = "Message is required";
    
    if (empty($errors)) {
        // Save to database (optional)
        $query = "INSERT INTO contact_messages (name, email, phone, subject, message) 
                  VALUES ('$name', '$email', " . ($phone ? "'$phone'" : "NULL") . ", '$subject', '$message')";
        
        // Send email (configure your mail settings)
        $to = "info@greenearthngo.org";
        $email_subject = "Contact Form: $subject";
        $email_body = "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message";
        $headers = "From: $email";
        
        // mail($to, $email_subject, $email_body, $headers);
        
        $_SESSION['success_message'] = "Thank you for contacting us. We'll get back to you soon!";
    } else {
        $_SESSION['error_message'] = implode("<br>", $errors);
    }
    
    redirect('contact.php');
}
?>