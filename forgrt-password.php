<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    
    $query = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Save token to database (you need a password_resets table)
        mysqli_query($conn, "INSERT INTO password_resets (user_id, token, expires_at) 
                             VALUES ({$user['id']}, '$token', '$expiry')");
        
        // Send email
        $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/NGO/reset-password.php?token=$token";
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Click this link to reset your password: $reset_link\n\nThis link expires in 1 hour.";
        $headers = "From: noreply@greenearthngo.org";
        
        // mail($to, $subject, $message, $headers);
        
        $success = "Password reset link has been sent to your email.";
    } else {
        $error = "Email not found in our system.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password | Green Earth NGO</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div style="max-width: 400px; margin: 100px auto; padding: 40px; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h2 style="color: var(--primary-green); margin-bottom: 20px;">Reset Password</h2>
        
        <?php if($error): ?>
            <div style="background: #f44336; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div style="background: #4CAF50; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div style="margin-bottom: 20px;">
                <label for="email" style="display: block; margin-bottom: 8px; font-weight: 600;">Email Address</label>
                <input type="email" id="email" name="email" required style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            
            <button type="submit" style="width: 100%; padding: 15px; background: var(--primary-green); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;">Send Reset Link</button>
            
            <p style="text-align: center; margin-top: 20px;"><a href="login.php" style="color: var(--primary-green);">Back to Login</a></p>
        </form>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>