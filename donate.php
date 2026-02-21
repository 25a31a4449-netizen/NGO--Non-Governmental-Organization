<?php
require_once 'config.php';

$success_message = '';
$error_message = '';

// Handle donation form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $donor_name = mysqli_real_escape_string($conn, trim($_POST['donor_name']));
    $donor_email = mysqli_real_escape_string($conn, trim($_POST['donor_email']));
    $donor_phone = !empty($_POST['donor_phone']) ? mysqli_real_escape_string($conn, trim($_POST['donor_phone'])) : null;
    $amount = floatval($_POST['amount']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $message = !empty($_POST['message']) ? mysqli_real_escape_string($conn, trim($_POST['message'])) : null;
    $is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0;
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NULL';
    
    // Validation
    if (empty($donor_name) || empty($donor_email) || $amount <= 0) {
        $error_message = "Please fill in all required fields and enter a valid amount.";
    } elseif (!filter_var($donor_email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        // Generate a fake transaction ID (in real scenario, this would come from payment gateway)
        $transaction_id = 'TXN' . strtoupper(uniqid());
        
        $insert_query = "INSERT INTO donations (user_id, donor_name, donor_email, donor_phone, amount, payment_method, transaction_id, message, is_anonymous) 
                         VALUES ($user_id, '$donor_name', '$donor_email', " . 
                         ($donor_phone ? "'$donor_phone'" : "NULL") . ", 
                         $amount, '$payment_method', '$transaction_id', " . 
                         ($message ? "'$message'" : "NULL") . ", $is_anonymous)";
        
        if (mysqli_query($conn, $insert_query)) {
            // Update total funds
            mysqli_query($conn, "UPDATE funds_tracking SET total_funds = total_funds + $amount WHERE id = 1");
            
            $success_message = "Thank you for your donation of $$amount! Your transaction ID is: $transaction_id";
        } else {
            $error_message = "Donation failed: " . mysqli_error($conn);
        }
    }
}

// Get total funds
$funds_query = "SELECT total_funds FROM funds_tracking WHERE id = 1";
$funds_result = mysqli_query($conn, $funds_query);
$total_funds = mysqli_fetch_assoc($funds_result)['total_funds'] ?? 0;

// Get recent donations (for view funds page)
$recent_donations_query = "SELECT donor_name, amount, donation_date, is_anonymous FROM donations ORDER BY donation_date DESC LIMIT 10";
$recent_donations_result = mysqli_query($conn, $recent_donations_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Earth NGO | Donate Funds</title>
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
        
        /* Navbar Styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 5%;
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
            width: 65px;
            height: 65px;
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
            font-size: 38px;
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
            font-size: 32px;
            font-weight: 800;
            color: var(--primary-green);
            line-height: 1.1;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        
        .ngo-fullform .fullform-text {
            font-size: 14px;
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
            gap: 30px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            transition: all 0.3s;
            font-size: 16px;
            padding: 8px 0;
            position: relative;
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
            padding: 12px 30px !important;
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
            padding: 12px 30px !important;
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
            background: linear-gradient(rgba(255, 215, 0, 0.85), rgba(46,125,50,0.85)), 
            url('https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        
        .page-header h1 {
            font-size: 56px;
            margin-bottom: 15px;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
        }
        
        .page-header p {
            font-size: 22px;
            max-width: 700px;
            margin: 0 auto;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
        }
        
        /* Total Funds Section */
        .total-funds {
            background: linear-gradient(135deg, var(--donate-gold), #e6c200);
            padding: 40px 5%;
            text-align: center;
            color: var(--text-dark);
        }
        
        .total-funds h2 {
            font-size: 36px;
            margin-bottom: 15px;
        }
        
        .total-amount {
            font-size: 64px;
            font-weight: 800;
            color: var(--primary-green);
            text-shadow: 2px 2px 4px rgba(255,255,255,0.5);
        }
        
        /* Donation Form */
        .donation-section {
            padding: 60px 5%;
            background-color: var(--light-gray);
        }
        
        .donation-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        
        .donation-container h2 {
            color: var(--primary-green);
            font-size: 32px;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }
        
        .donation-container h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(to right, var(--primary-green), var(--donate-gold));
            border-radius: 2px;
        }
        
        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
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
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
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
        
        .amount-presets {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .amount-preset {
            background-color: var(--light-gray);
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .amount-preset:hover {
            background-color: var(--primary-green);
            color: white;
            border-color: var(--primary-green);
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
        }
        
        .checkbox-group input {
            width: auto;
        }
        
        .donate-btn {
            background-color: var(--donate-gold);
            color: var(--text-dark);
            padding: 18px 45px;
            border: none;
            border-radius: 50px;
            font-size: 20px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            box-shadow: 0 10px 25px rgba(255, 215, 0, 0.3);
        }
        
        .donate-btn:hover {
            background-color: #e6c200;
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(255, 215, 0, 0.4);
        }
        
        /* Recent Donations */
        .recent-donations {
            padding: 60px 5%;
            background-color: white;
        }
        
        .section-title {
            text-align: center;
            font-size: 42px;
            color: var(--primary-green);
            margin-bottom: 50px;
            position: relative;
            padding-bottom: 20px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(to right, var(--primary-green), var(--donate-gold));
            border-radius: 2px;
        }
        
        .donations-table {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 15px;
            overflow: hidden;
        }
        
        .donations-table th {
            background-color: var(--primary-green);
            color: white;
            padding: 15px;
            text-align: left;
        }
        
        .donations-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .donations-table tr:hover {
            background-color: var(--light-gray);
        }
        
        .anonymous {
            color: #999;
            font-style: italic;
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
            <h1>Make a Donation</h1>
            <p>Your support helps us protect our planet and create a sustainable future</p>
        </div>
    </section>

    <!-- Total Funds Section -->
    <section class="total-funds">
        <h2>Total Funds Raised</h2>
        <div class="total-amount">$<?php echo number_format($total_funds, 2); ?></div>
        <p style="margin-top: 15px; font-size: 18px;">Every dollar makes a difference!</p>
    </section>

    <!-- Donation Form Section -->
    <section class="donation-section">
        <div class="donation-container">
            <h2>Donate Now</h2>
            
            <?php if($success_message): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="donor_name">Full Name *</label>
                    <input type="text" id="donor_name" name="donor_name" placeholder="Enter your full name" required
                           value="<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="donor_email">Email Address *</label>
                        <input type="email" id="donor_email" name="donor_email" placeholder="Enter your email" required
                               value="<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="donor_phone">Phone Number</label>
                        <input type="tel" id="donor_phone" name="donor_phone" placeholder="Enter your phone">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Select Amount *</label>
                    <div class="amount-presets">
                        <div class="amount-preset" onclick="setAmount(10)">$10</div>
                        <div class="amount-preset" onclick="setAmount(25)">$25</div>
                        <div class="amount-preset" onclick="setAmount(50)">$50</div>
                        <div class="amount-preset" onclick="setAmount(100)">$100</div>
                    </div>
                    <input type="number" id="amount" name="amount" placeholder="Or enter custom amount" min="1" step="0.01" required style="margin-top: 10px;">
                </div>
                
                <div class="form-group">
                    <label for="payment_method">Payment Method *</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="">Select payment method</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="debit_card">Debit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cash">Cash</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="message">Message (Optional)</label>
                    <textarea id="message" name="message" placeholder="Leave a message with your donation..." rows="3"></textarea>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="is_anonymous" name="is_anonymous">
                    <label for="is_anonymous">Make this donation anonymous</label>
                </div>
                
                <button type="submit" class="donate-btn">Complete Donation</button>
            </form>
        </div>
    </section>

    <!-- Recent Donations Section -->
    <section class="recent-donations">
        <h2 class="section-title">Recent Donations</h2>
        
        <table class="donations-table">
            <thead>
                <tr>
                    <th>Donor</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($recent_donations_result) > 0): ?>
                    <?php while($donation = mysqli_fetch_assoc($recent_donations_result)): ?>
                        <tr>
                            <td>
                                <?php if($donation['is_anonymous']): ?>
                                    <span class="anonymous">Anonymous</span>
                                <?php else: ?>
                                    <?php echo htmlspecialchars($donation['donor_name']); ?>
                                <?php endif; ?>
                            </td>
                            <td><strong>$<?php echo number_format($donation['amount'], 2); ?></strong></td>
                            <td><?php echo date('M j, Y', strtotime($donation['donation_date'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 30px;">No donations yet. Be the first!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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

    <script>
        function setAmount(amount) {
            document.getElementById('amount').value = amount;
        }
    </script>
</body>
</html>