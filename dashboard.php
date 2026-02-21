<?php
// dashboard.php - User dashboard (protected page)
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

// Get user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Get user's registered events
$events_query = "SELECT * FROM event_registrations WHERE user_id = $user_id ORDER BY registration_date DESC LIMIT 5";
$events_result = mysqli_query($conn, $events_query);

// Get user's volunteer hours
$hours_query = "SELECT SUM(hours) as total_hours, COUNT(*) as activities FROM volunteer_hours WHERE user_id = $user_id AND status = 'approved'";
$hours_result = mysqli_query($conn, $hours_query);
$hours_data = mysqli_fetch_assoc($hours_result);

// Get user's donations
$donations_query = "SELECT COUNT(*) as donation_count, SUM(amount) as total_donated FROM donations WHERE user_id = $user_id";
$donations_result = mysqli_query($conn, $donations_query);
$donations_data = mysqli_fetch_assoc($donations_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Earth NGO | Dashboard</title>
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
            background-color: #f4f4f4;
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
        
        .dashboard-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }
        
        .welcome-section {
            background: white;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .welcome-section h1 {
            color: var(--primary-green);
            margin-bottom: 10px;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .dashboard-card h3 {
            color: var(--primary-green);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--secondary-cream);
        }
        
        .user-info p {
            margin: 10px 0;
            color: #666;
        }
        
        .user-info strong {
            color: var(--text-dark);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }
        
        .stat-box {
            text-align: center;
            padding: 15px;
            background: var(--light-gray);
            border-radius: 10px;
        }
        
        .stat-box .number {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-green);
        }
        
        .stat-box .label {
            font-size: 14px;
            color: #666;
        }
        
        .event-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .event-item:last-child {
            border-bottom: none;
        }
        
        .event-item h4 {
            color: var(--primary-green);
            margin-bottom: 5px;
        }
        
        .event-item p {
            color: #666;
            font-size: 14px;
        }
        
        .logout-btn {
            background-color: var(--register-orange);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
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

    <div class="dashboard-container">
        <div class="welcome-section">
            <h1>Welcome, <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>!</h1>
            <p>Member since: <?php echo date('F j, Y', strtotime($user['registration_date'])); ?></p>
            <p>Membership Type: <strong><?php echo ucfirst($user['membership_type']); ?></strong></p>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>Your Impact</h3>
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="number"><?php echo $hours_data['total_hours'] ?? 0; ?></div>
                        <div class="label">Volunteer Hours</div>
                    </div>
                    <div class="stat-box">
                        <div class="number"><?php echo $donations_data['donation_count'] ?? 0; ?></div>
                        <div class="label">Donations</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">$<?php echo number_format($donations_data['total_donated'] ?? 0, 2); ?></div>
                        <div class="label">Amount Donated</div>
                    </div>
                </div>
            </div>

            <div class="dashboard-card">
                <h3>Your Profile</h3>
                <div class="user-info">
                    <p><strong>Name:</strong> <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>
                    <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                    <p><strong>Phone:</strong> <?php echo $user['phone'] ?: 'Not provided'; ?></p>
                    <p><strong>Newsletter:</strong> <?php echo $user['newsletter_subscription'] ? 'Subscribed' : 'Not subscribed'; ?></p>
                </div>
            </div>

            <div class="dashboard-card">
                <h3>Quick Actions</h3>
                <ul style="list-style: none;">
                    <li style="margin: 15px 0;"><a href="events.php" style="color: var(--primary-green);">📅 Browse Events</a></li>
                    <li style="margin: 15px 0;"><a href="volunteer.php" style="color: var(--primary-green);">⏰ Log Volunteer Hours</a></li>
                    <li style="margin: 15px 0;"><a href="donate.php" style="color: var(--primary-green);">💰 Make a Donation</a></li>
                    <li style="margin: 15px 0;"><a href="blog.php" style="color: var(--primary-green);">📝 Read Our Blog</a></li>
                </ul>
            </div>
        </div>

        <div class="dashboard-card">
            <h3>Your Upcoming Events</h3>
            <?php if(mysqli_num_rows($events_result) > 0): ?>
                <?php while($event = mysqli_fetch_assoc($events_result)): ?>
                    <div class="event-item">
                        <h4><?php echo $event['event_name']; ?></h4>
                        <p>📅 <?php echo $event['event_date']; ?> | 📍 <?php echo $event['event_location']; ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: #999; text-align: center; padding: 20px;">No events registered yet. <a href="events.php" style="color: var(--primary-green);">Browse events</a></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>