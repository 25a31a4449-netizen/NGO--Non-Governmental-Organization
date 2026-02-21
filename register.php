<?php
// register.php - User registration page with database integration
require_once 'config.php';

$error = '';
$success = '';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Process registration form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize form data
    $first_name = mysqli_real_escape_string($conn, trim($_POST['first_name']));
    $last_name = mysqli_real_escape_string($conn, trim($_POST['last_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = !empty($_POST['phone']) ? mysqli_real_escape_string($conn, trim($_POST['phone'])) : null;
    $dob = !empty($_POST['dob']) ? mysqli_real_escape_string($conn, $_POST['dob']) : null;
    $address = !empty($_POST['address']) ? mysqli_real_escape_string($conn, trim($_POST['address'])) : null;
    $membership_type = mysqli_real_escape_string($conn, $_POST['membership_type']);
    $hear_about = !empty($_POST['hear_about']) ? mysqli_real_escape_string($conn, $_POST['hear_about']) : null;
    $interests = !empty($_POST['interests']) ? mysqli_real_escape_string($conn, trim($_POST['interests'])) : null;
    $newsletter = isset($_POST['newsletter']) ? 1 : 0;
    
    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($membership_type)) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $check_query = "SELECT id FROM users WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Email already registered. Please use a different email or <a href='login.php'>login here</a>.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user into database
            $insert_query = "INSERT INTO users (first_name, last_name, email, password, phone, date_of_birth, address, membership_type, hear_about, interests, newsletter_subscription) 
                            VALUES ('$first_name', '$last_name', '$email', '$hashed_password', " . 
                            ($phone ? "'$phone'" : "NULL") . ", " . 
                            ($dob ? "'$dob'" : "NULL") . ", " . 
                            ($address ? "'$address'" : "NULL") . ", " . 
                            "'$membership_type'" . ", " . 
                            ($hear_about ? "'$hear_about'" : "NULL") . ", " . 
                            ($interests ? "'$interests'" : "NULL") . ", $newsletter)";
            
            if (mysqli_query($conn, $insert_query)) {
                // Add to newsletter if subscribed
                if ($newsletter) {
                    $check_subscriber = mysqli_query($conn, "SELECT id FROM newsletter_subscribers WHERE email = '$email'");
                    if (mysqli_num_rows($check_subscriber) == 0) {
                        mysqli_query($conn, "INSERT INTO newsletter_subscribers (email) VALUES ('$email')");
                    }
                }
                
                $success = "Registration successful! Redirecting to login page...";
                // Redirect to login page after 2 seconds
                header("refresh:2;url=login.php");
            } else {
                $error = "Registration failed: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Earth NGO | Register</title>
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
            background: linear-gradient(rgba(230,126,34,0.85), rgba(46,125,50,0.85)), 
            url('https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center 30%;
            background-attachment: fixed;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.3) 100%);
            pointer-events: none;
        }
        
        .page-header div {
            position: relative;
            z-index: 2;
        }
        
        .page-header h1 {
            font-size: 64px;
            margin-bottom: 15px;
            text-shadow: 3px 3px 12px rgba(0,0,0,0.6);
            font-weight: 800;
            animation: fadeInUp 1s ease;
        }
        
        .page-header p {
            font-size: 24px;
            max-width: 700px;
            margin: 0 auto;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
            animation: fadeInUp 1s ease 0.2s both;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Registration Section */
        .register-section {
            padding: 80px 5%;
            background-color: var(--light-gray);
        }
        
        .register-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1.5fr;
        }
        
        /* Info Panel */
        .info-panel {
            background: linear-gradient(145deg, var(--primary-green), #1b5e20);
            color: white;
            padding: 50px 40px;
        }
        
        .info-panel h2 {
            font-size: 32px;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .info-panel h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--register-orange);
        }
        
        .benefits-list {
            list-style: none;
            margin: 30px 0;
        }
        
        .benefits-list li {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 16px;
        }
        
        .benefits-list li::before {
            content: "✓";
            background: var(--register-orange);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }
        
        .testimonial-small {
            background: rgba(255,255,255,0.1);
            padding: 25px;
            border-radius: 15px;
            margin-top: 30px;
            border-left: 4px solid var(--register-orange);
        }
        
        .testimonial-small p {
            font-style: italic;
            margin-bottom: 10px;
            line-height: 1.6;
        }
        
        .testimonial-small h4 {
            color: var(--secondary-cream);
        }
        
        /* Form Panel */
        .form-panel {
            padding: 50px;
        }
        
        .form-panel h2 {
            color: var(--primary-green);
            font-size: 28px;
            margin-bottom: 30px;
        }
        
        .error-message {
            background-color: #f44336;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
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
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(46,125,50,0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .terms {
            margin: 25px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .terms input {
            width: auto;
        }
        
        .terms a {
            color: var(--primary-green);
            text-decoration: none;
        }
        
        .terms a:hover {
            text-decoration: underline;
        }
        
        .submit-btn {
            background-color: var(--register-orange);
            color: white;
            padding: 18px 45px;
            border: none;
            border-radius: 60px;
            font-size: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            box-shadow: 0 10px 25px rgba(230,126,34,0.3);
        }
        
        .submit-btn:hover {
            background-color: #d35400;
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(230,126,34,0.4);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-link a {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
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
            <h1>Join Our Movement</h1>
            <p>Become a member and help us create a sustainable future</p>
        </div>
    </section>

    <!-- Registration Section -->
    <section class="register-section">
        <div class="register-container">
            <!-- Info Panel -->
            <div class="info-panel">
                <h2>Why Join?</h2>
                <ul class="benefits-list">
                    <li>Participate in tree planting and conservation projects</li>
                    <li>Access exclusive workshops and training programs</li>
                    <li>Network with environmental professionals</li>
                    <li>Receive our monthly newsletter and updates</li>
                    <li>Vote in organizational decisions</li>
                    <li>Discounts on events and merchandise</li>
                    <li>Certificate of membership</li>
                </ul>
                
                <div class="testimonial-small">
                    <p>"Joining Green Earth was the best decision I made. I've met incredible people and contributed to real change."</p>
                    <h4>- David Kim, Member since 2020</h4>
                </div>
            </div>
            
            <!-- Form Panel -->
            <div class="form-panel">
                <h2>Registration Form</h2>
                
                <?php if($error): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if($success): ?>
                    <div class="success-message"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" id="first_name" name="first_name" placeholder="John" required 
                                   value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" placeholder="Doe" required
                                   value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" placeholder="john.doe@example.com" required
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password *</label>
                            <input type="password" id="password" name="password" placeholder="Min. 6 characters" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password *</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter password" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="+1 234 567 8900"
                                   value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="dob"
                                   value="<?php echo isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" placeholder="Street address, City, State, ZIP"
                               value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="membership_type">Membership Type *</label>
                        <select id="membership_type" name="membership_type" required>
                            <option value="">Select membership type</option>
                            <option value="individual" <?php echo (isset($_POST['membership_type']) && $_POST['membership_type'] == 'individual') ? 'selected' : ''; ?>>Individual - $25/year</option>
                            <option value="family" <?php echo (isset($_POST['membership_type']) && $_POST['membership_type'] == 'family') ? 'selected' : ''; ?>>Family - $45/year</option>
                            <option value="student" <?php echo (isset($_POST['membership_type']) && $_POST['membership_type'] == 'student') ? 'selected' : ''; ?>>Student - $15/year</option>
                            <option value="lifetime" <?php echo (isset($_POST['membership_type']) && $_POST['membership_type'] == 'lifetime') ? 'selected' : ''; ?>>Lifetime - $500 one-time</option>
                            <option value="corporate" <?php echo (isset($_POST['membership_type']) && $_POST['membership_type'] == 'corporate') ? 'selected' : ''; ?>>Corporate - $500/year</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="hear_about">How did you hear about us?</label>
                        <select id="hear_about" name="hear_about">
                            <option value="">Select an option</option>
                            <option value="social" <?php echo (isset($_POST['hear_about']) && $_POST['hear_about'] == 'social') ? 'selected' : ''; ?>>Social Media</option>
                            <option value="friend" <?php echo (isset($_POST['hear_about']) && $_POST['hear_about'] == 'friend') ? 'selected' : ''; ?>>Friend/Family</option>
                            <option value="event" <?php echo (isset($_POST['hear_about']) && $_POST['hear_about'] == 'event') ? 'selected' : ''; ?>>Event</option>
                            <option value="search" <?php echo (isset($_POST['hear_about']) && $_POST['hear_about'] == 'search') ? 'selected' : ''; ?>>Search Engine</option>
                            <option value="ad" <?php echo (isset($_POST['hear_about']) && $_POST['hear_about'] == 'ad') ? 'selected' : ''; ?>>Advertisement</option>
                            <option value="other" <?php echo (isset($_POST['hear_about']) && $_POST['hear_about'] == 'other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="interests">Areas of Interest (Optional)</label>
                        <textarea id="interests" name="interests" placeholder="e.g., Reforestation, Ocean Conservation, Wildlife Protection"><?php echo isset($_POST['interests']) ? htmlspecialchars($_POST['interests']) : ''; ?></textarea>
                    </div>
                    
                    <div class="terms">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">I agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a> *</label>
                    </div>
                    
                    <div class="terms">
                        <input type="checkbox" id="newsletter" name="newsletter" checked>
                        <label for="newsletter">Subscribe to our newsletter for updates</label>
                    </div>
                    
                    <button type="submit" class="submit-btn">Complete Registration</button>
                    
                    <div class="login-link">
                        Already have an account? <a href="login.php">Login here</a>
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