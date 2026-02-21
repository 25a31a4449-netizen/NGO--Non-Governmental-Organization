<?php
// login.php - User login page
require_once 'config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;
    
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        // Get user from database
        $sql = "SELECT id, first_name, last_name, email, password FROM users WHERE email = '$email' AND status = 'active'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email'] = $user['email'];
                
                // Update last login
                mysqli_query($conn, "UPDATE users SET last_login = NOW() WHERE id = " . $user['id']);
                
                // Redirect to home page or the page they came from
                $redirect = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php';
                unset($_SESSION['redirect_url']);
                redirect($redirect);
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Earth NGO | Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        :root {
            --primary-green: #2e7d32;
            --secondary-cream: #f5f5dc;
            --accent-brown: #8b5a2b;
            --register-orange: #e67e22;
            --donate-gold: #ffd700;
            --text-dark: #2c3e50;
            --light-gray: #f4f4f4;
        }
        
        body {
            background-color: #ffffff;
            color: var(--text-dark);
        }
        
        /* Navbar Styles - ENLARGED */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 5%;
            background-color: white;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .green-earth-logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(145deg, #2e7d32, #1b5e20, #8b5a2b);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 12px rgba(0,60,0,0.4), inset 0 2px 4px rgba(255,255,255,0.5);
            border: 3px solid #f5f5dc;
            position: relative;
            animation: pulse 2s infinite ease-in-out;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 6px 12px rgba(0,60,0,0.4);
            }
            50% {
                box-shadow: 0 8px 20px rgba(46,125,50,0.7), 0 0 0 3px rgba(245,245,220,0.5);
            }
            100% {
                box-shadow: 0 6px 12px rgba(0,60,0,0.4);
            }
        }
        
        .green-earth-logo::before {
            content: "🌍";
            font-size: 42px;
            filter: drop-shadow(2px 4px 4px rgba(0,0,0,0.3));
            transform: scale(1.1);
            transition: transform 0.3s;
        }
        
        .green-earth-logo:hover::before {
            transform: scale(1.2) rotate(10deg);
        }
        
        .green-earth-logo::after {
            content: "";
            position: absolute;
            top: 5px;
            left: 10px;
            width: 15px;
            height: 15px;
            background: rgba(255,255,255,0.4);
            border-radius: 50%;
            filter: blur(2px);
        }
        
        .ngo-fullform {
            display: flex;
            flex-direction: column;
        }
        
        .ngo-fullform .ngo {
            font-size: 34px;
            font-weight: 800;
            color: var(--primary-green);
            line-height: 1.1;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        
        .ngo-fullform .fullform-text {
            font-size: 15px;
            color: var(--accent-brown);
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            background: linear-gradient(90deg, var(--accent-brown), var(--primary-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 25px;
            flex-wrap: wrap;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            transition: all 0.3s;
            font-size: 16px;
            padding: 8px 0;
            position: relative;
            white-space: nowrap;
        }
        
        .nav-links a:hover {
            color: var(--primary-green);
        }
        
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-green);
            transition: width 0.3s;
        }
        
        .nav-links a:hover::after {
            width: 100%;
        }
        
        .donate-nav-btn {
            background-color: var(--donate-gold) !important;
            color: var(--text-dark) !important;
            padding: 12px 25px !important;
            border-radius: 50px;
            font-weight: 700 !important;
            box-shadow: 0 6px 15px rgba(255, 215, 0, 0.5);
            border: 2px solid transparent;
            transition: all 0.3s !important;
            animation: glow 2s infinite;
        }
        
        .donate-nav-btn:hover {
            background-color: #e6c200 !important;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 25px rgba(255, 215, 0, 0.7);
            border-color: white;
        }
        
        @keyframes glow {
            0% {
                box-shadow: 0 6px 15px rgba(255, 215, 0, 0.5);
            }
            50% {
                box-shadow: 0 8px 25px rgba(255, 215, 0, 0.8), 0 0 0 3px rgba(255, 215, 0, 0.3);
            }
            100% {
                box-shadow: 0 6px 15px rgba(255, 215, 0, 0.5);
            }
        }
        
        .register-btn {
            background-color: var(--register-orange) !important;
            color: white !important;
            padding: 12px 25px !important;
            border-radius: 50px;
            font-weight: 700 !important;
            box-shadow: 0 6px 15px rgba(230,126,34,0.5);
            border: 2px solid transparent;
            transition: all 0.3s !important;
            letter-spacing: 0.5px;
        }
        
        .register-btn:hover {
            background-color: #d35400 !important;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 25px rgba(230,126,34,0.7);
            border-color: white;
        }
        
        .register-btn::after {
            display: none !important;
        }
        
        /* Page Header */
        .page-header {
            background: linear-gradient(rgba(46,125,50,0.9), rgba(46,125,50,0.9)), 
            url('https://images.unsplash.com/photo-1559027615-cd4628902d4a?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        
        .page-header h1 {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .page-header p {
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto;
        }
        
        /* Login Section */
        .login-section {
            padding: 60px 5%;
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
        }
        
        .login-box {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        
        .login-box h2 {
            color: var(--primary-green);
            font-size: 32px;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }
        
        .login-box h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-green), var(--accent-brown));
            border-radius: 2px;
        }
        
        .error-message {
            background-color: #f44336;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(46,125,50,0.1);
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .remember input {
            width: auto;
        }
        
        .forgot a {
            color: var(--primary-green);
            text-decoration: none;
        }
        
        .forgot a:hover {
            text-decoration: underline;
        }
        
        .login-btn {
            background-color: var(--primary-green);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
        }
        
        .login-btn:hover {
            background-color: #1b5e20;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(46,125,50,0.3);
        }
        
        .register-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #eee;
        }
        
        .register-link p {
            color: #666;
        }
        
        .register-link a {
            color: var(--register-orange);
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        /* Footer */
        footer {
            background-color: #1e2a2f;
            color: white;
            padding: 60px 5% 30px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 50px;
            max-width: 1300px;
            margin: 0 auto 50px;
        }
        
        .footer-section h3 {
            color: var(--secondary-cream);
            margin-bottom: 25px;
            font-size: 22px;
            position: relative;
            padding-bottom: 12px;
        }
        
        .footer-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--primary-green);
        }
        
        .footer-section p, .footer-section a {
            color: #b0c4c9;
            text-decoration: none;
            line-height: 2;
        }
        
        .footer-section a:hover {
            color: var(--secondary-cream);
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #2a3a40;
            color: #8a9ca0;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar - ENLARGED with CORRECT ORDER -->
<nav class="navbar">
    <div class="logo-container">
        <div class="green-earth-logo" title="Green Earth NGO"></div>
        <div class="ngo-fullform">
            <div class="ngo">NGO</div>
            <div class="fullform-text">Non-governmental organization</div>
        </div>
    </div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="social.php">Social Services</a>
        <a href="benefits.php">Benefits</a>
        <a href="events.php">Events</a>
        <a href="collaborations.php">Collaborations</a>
        <a href="blog.php">Blog</a>
        <a href="gallery.php">Gallery</a>
        <a href="volunteer.php">Volunteer</a>
        <a href="branches.php">Branches</a>
        <a href="contact.php">Contact Us</a>
        <a href="donate.php" class="donate-nav-btn">💰 Donate</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" class="register-btn">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php" class="register-btn">Register</a>
        <?php endif; ?>
    </div>
</nav>

    <!-- Page Header -->
    <section class="page-header">
        <div>
            <h1>Welcome Back</h1>
            <p>Login to access your account and manage your contributions</p>
        </div>
    </section>

    <!-- Login Section -->
    <section class="login-section">
        <div class="login-container">
            <div class="login-box">
                <h2>Login</h2>
                
                <?php if($error): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    
                    <div class="remember-forgot">
                        <div class="remember">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <div class="forgot">
                            <a href="forgot-password.php">Forgot Password?</a>
                        </div>
                    </div>
                    
                    <button type="submit" class="login-btn">Login</button>
                    
                    <div class="register-link">
                        <p>Don't have an account?</p>
                        <a href="register.php">Register Now</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Green Earth NGO</h3>
                <p>Non-governmental organization dedicated to environmental conservation since 2010.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <p><a href="index.php">Home</a><br>
                <a href="social.php">Social Services</a><br>
                <a href="benefits.php">Benefits</a><br>
                <a href="events.php">Events</a><br>
                <a href="branches.php">Branches</a><br>
                <a href="contact.php">Contact</a><br>
                <a href="collaborations.php">Collaborations</a><br>
                <a href="blog.php">Blog</a><br>
                <a href="gallery.php">Gallery</a><br>
                <a href="volunteer.php">Volunteer</a><br>
                <a href="donate.php">Donate</a></p>
            </div>
            <div class="footer-section">
                <h3>Contact Info</h3>
                <p>Email: info@greenearthngo.org<br>
                Phone: +1 (555) 123-4567<br>
                Address: 123 Green Avenue, Earth City</p>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2024 Green Earth NGO - Non-governmental organization. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>