<?php require_once 'config.php'; ?>
<?php
$message = '';
$error = '';

// Handle volunteer hours submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $event_name = mysqli_real_escape_string($conn, $_POST['event_name']);
    $hours = floatval($_POST['hours']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $activity_date = mysqli_real_escape_string($conn, $_POST['activity_date']);
    
    if (empty($event_name) || $hours <= 0 || empty($activity_date)) {
        $error = "Please fill in all required fields correctly.";
    } else {
        // Changed from 'pending' to 'confirmed'
        $query = "INSERT INTO volunteer_hours (user_id, event_name, hours, description, activity_date, status) 
                  VALUES ($user_id, '$event_name', $hours, '$description', '$activity_date', 'confirmed')";
        
        if (mysqli_query($conn, $query)) {
            $message = "✅ Volunteer hours logged successfully! Status: Confirmed";
        } else {
            $error = "❌ Error logging hours: " . mysqli_error($conn);
        }
    }
}

// Get volunteer leaderboard - changed from 'approved' to 'confirmed'
$leaderboard_query = "SELECT u.first_name, u.last_name, 
                      COALESCE(SUM(vh.hours), 0) as total_hours
                      FROM users u 
                      LEFT JOIN volunteer_hours vh ON u.id = vh.user_id AND vh.status = 'confirmed' 
                      GROUP BY u.id 
                      ORDER BY total_hours DESC 
                      LIMIT 10";
$leaderboard_result = mysqli_query($conn, $leaderboard_query);

// Get user's volunteer history
$user_hours = 0;
$user_activities = 0;
$user_logs = [];

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Get volunteer history
    $history_query = "SELECT * FROM volunteer_hours WHERE user_id = $user_id ORDER BY activity_date DESC";
    $history_result = mysqli_query($conn, $history_query);
    
    // Get total confirmed hours - changed from 'approved' to 'confirmed'
    $total_query = "SELECT COALESCE(SUM(hours), 0) as total, COUNT(*) as count 
                    FROM volunteer_hours WHERE user_id = $user_id AND status = 'confirmed'";
    $total_result = mysqli_query($conn, $total_query);
    $total_data = mysqli_fetch_assoc($total_result);
    $user_hours = $total_data['total'] ?? 0;
    $user_activities = $total_data['count'] ?? 0;
}

// Get overall stats - changed from 'approved' to 'confirmed'
$stats_query = "SELECT 
                COUNT(DISTINCT user_id) as total_volunteers,
                COALESCE(SUM(hours), 0) as total_hours,
                COUNT(*) as total_activities
                FROM volunteer_hours WHERE status = 'confirmed'";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Get recent activities - changed from 'approved' to 'confirmed'
$recent_query = "SELECT vh.*, u.first_name, u.last_name 
                 FROM volunteer_hours vh
                 JOIN users u ON vh.user_id = u.id
                 WHERE vh.status = 'confirmed'
                 ORDER BY vh.activity_date DESC 
                 LIMIT 5";
$recent_result = mysqli_query($conn, $recent_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Earth NGO | Volunteer</title>
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
            --volunteer-blue: #3498db;
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
        
        /* Page Header - Modified to remove green color (now using dark overlay) */
        .page-header {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
            url('https://images.unsplash.com/photo-1559027615-cd4628902d4a?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
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
            font-size: 48px;
            margin-bottom: 15px;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
        }
        
        .page-header p {
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
        }
        
        /* Stats Bar */
        .stats-bar {
            background: var(--primary-green);
            padding: 30px 5%;
            color: white;
        }
        
        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            text-align: center;
        }
        
        .stat-item {
            padding: 20px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 16px;
            opacity: 0.9;
        }
        
        /* Main Container */
        .container {
            max-width: 1300px;
            margin: 50px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        
        /* Cards */
        .card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .card h2 {
            color: var(--primary-green);
            font-size: 24px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--secondary-cream);
        }
        
        /* Messages */
        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* Form */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 600;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-group input:focus,
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
        
        .btn {
            background: var(--primary-green);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: #1b5e20;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(46,125,50,0.3);
        }
        
        /* User Stats */
        .user-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-box {
            background: var(--light-gray);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-green);
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
        }
        
        /* Leaderboard */
        .leaderboard-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
            transition: background 0.3s;
        }
        
        .leaderboard-item:hover {
            background: var(--light-gray);
        }
        
        .rank {
            width: 35px;
            height: 35px;
            background: var(--light-gray);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 15px;
        }
        
        .rank-1 {
            background: var(--donate-gold);
            color: var(--text-dark);
        }
        
        .rank-2 {
            background: #c0c0c0;
            color: var(--text-dark);
        }
        
        .rank-3 {
            background: #cd7f32;
            color: white;
        }
        
        .volunteer-info {
            flex: 1;
        }
        
        .volunteer-name {
            font-weight: 600;
        }
        
        .volunteer-hours {
            font-weight: 700;
            color: var(--primary-green);
        }
        
        /* Activity List */
        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            background: var(--light-gray);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 20px;
        }
        
        .activity-details {
            flex: 1;
        }
        
        .activity-name {
            font-weight: 600;
            margin-bottom: 3px;
        }
        
        .activity-meta {
            font-size: 13px;
            color: #666;
        }
        
        /* Table */
        .table-container {
            overflow-x: auto;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th {
            background: var(--primary-green);
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        .table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .table tr:hover {
            background: var(--light-gray);
        }
        
        .status-badge {
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        
        /* Login Prompt */
        .login-prompt {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .login-prompt h3 {
            font-size: 32px;
            color: var(--primary-green);
            margin-bottom: 15px;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin: 40px 0;
        }
        
        .feature-item {
            text-align: center;
        }
        
        .feature-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        
        .login-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .login-btn {
            background: var(--primary-green);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .login-btn:hover {
            background: #1b5e20;
            transform: translateY(-2px);
        }
        
        .register-large {
            background: var(--register-orange);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .register-large:hover {
            background: #d35400;
            transform: translateY(-2px);
        }
        
        /* Footer */
        footer {
            background-color: #1e2a2f;
            color: white;
            padding: 60px 5% 30px;
            margin-top: 60px;
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
        
        /* Responsive */
        @media (max-width: 992px) {
            .container {
                grid-template-columns: 1fr;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .user-stats {
                grid-template-columns: 1fr;
            }
            
            .feature-grid {
                grid-template-columns: 1fr;
            }
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

    <!-- Page Header - Now with dark overlay instead of green -->
    <section class="page-header">
        <div>
            <h1>Volunteer With Us</h1>
            <p>Make a difference in your community and track your impact</p>
        </div>
    </section>

    <!-- Stats Bar -->
    <section class="stats-bar">
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-number"><?php echo number_format($stats['total_volunteers'] ?? 0); ?></div>
                <div class="stat-label">Active Volunteers</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo number_format($stats['total_hours'] ?? 0, 1); ?></div>
                <div class="stat-label">Total Hours</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo number_format($stats['total_activities'] ?? 0); ?></div>
                <div class="stat-label">Activities</div>
            </div>
        </div>
    </section>

    <?php if(isset($_SESSION['user_id'])): ?>
        <!-- Logged In View -->
        <div class="container">
            <!-- Left Column -->
            <div>
                <!-- User Stats -->
                <div class="user-stats">
                    <div class="stat-box">
                        <div class="stat-number"><?php echo number_format($user_hours, 1); ?></div>
                        <div class="stat-label">Your Hours</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number"><?php echo $user_activities; ?></div>
                        <div class="stat-label">Activities</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">🌱</div>
                        <div class="stat-label">Volunteer</div>
                    </div>
                </div>

                <!-- Log Hours Form -->
                <div class="card">
                    <h2>⏰ Log Your Volunteer Hours</h2>
                    
                    <?php if($message): ?>
                        <div class="message success">
                            <span>✅</span>
                            <span><?php echo $message; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($error): ?>
                        <div class="message error">
                            <span>❌</span>
                            <span><?php echo $error; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="event_name">Activity Name *</label>
                            <input type="text" id="event_name" name="event_name" placeholder="e.g., Beach Cleanup, Tree Planting" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="hours">Hours *</label>
                                <input type="number" id="hours" name="hours" step="0.5" min="0.5" placeholder="2.5" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="activity_date">Date *</label>
                                <input type="date" id="activity_date" name="activity_date" max="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description (Optional)</label>
                            <textarea id="description" name="description" rows="3" placeholder="Tell us about your volunteer activity..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn">Log Hours</button>
                    </form>
                </div>

                <!-- Recent Activities -->
                <div class="card">
                    <h2>🔄 Recent Activities</h2>
                    <?php if(mysqli_num_rows($recent_result) > 0): ?>
                        <?php while($activity = mysqli_fetch_assoc($recent_result)): ?>
                            <div class="activity-item">
                                <div class="activity-icon">⭐</div>
                                <div class="activity-details">
                                    <div class="activity-name"><?php echo $activity['event_name']; ?></div>
                                    <div class="activity-meta">
                                        <?php echo $activity['first_name'] . ' ' . $activity['last_name']; ?> • 
                                        <?php echo date('M d, Y', strtotime($activity['activity_date'])); ?> • 
                                        <?php echo $activity['hours']; ?> hours
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: #666; padding: 20px;">No recent activities</p>
                    <?php endif; ?>
                </div>

                <!-- Volunteer History -->
                <div class="card">
                    <h2>📋 Your Volunteer History</h2>
                    <?php if(isset($history_result) && mysqli_num_rows($history_result) > 0): ?>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Activity</th>
                                        <th>Hours</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($log = mysqli_fetch_assoc($history_result)): ?>
                                        <tr>
                                            <td><?php echo date('M j, Y', strtotime($log['activity_date'])); ?></td>
                                            <td><?php echo $log['event_name']; ?></td>
                                            <td><strong><?php echo number_format($log['hours'], 1); ?></strong></td>
                                            <td>
                                                <span class="status-badge status-confirmed">
                                                    ✓ <?php echo ucfirst($log['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 40px; background: var(--light-gray); border-radius: 10px;">
                            <p style="color: #666; margin-bottom: 15px;">No hours logged yet</p>
                            <p style="color: var(--primary-green);">Start your volunteer journey today!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column - Leaderboard -->
            <div>
                <div class="card">
                    <h2>🏆 Top Volunteers</h2>
                    <?php 
                    $rank = 1;
                    if(mysqli_num_rows($leaderboard_result) > 0): 
                        while($vol = mysqli_fetch_assoc($leaderboard_result)): 
                    ?>
                        <div class="leaderboard-item">
                            <div class="rank rank-<?php echo $rank; ?>"><?php echo $rank; ?></div>
                            <div class="volunteer-info">
                                <div class="volunteer-name">
                                    <?php echo $vol['first_name'] . ' ' . $vol['last_name']; ?>
                                </div>
                            </div>
                            <div class="volunteer-hours"><?php echo number_format($vol['total_hours'], 1); ?> hrs</div>
                        </div>
                    <?php 
                            $rank++;
                            endwhile; 
                        else: 
                    ?>
                        <p style="text-align: center; color: #666; padding: 20px;">No volunteers yet</p>
                    <?php endif; ?>
                </div>
                
                <!-- Quick Tips -->
                <div class="card">
                    <h2>💡 Volunteer Tips</h2>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 15px; display: flex; gap: 10px;">
                            <span style="color: var(--primary-green); font-size: 20px;">✓</span>
                            <span>Log your hours within 7 days</span>
                        </li>
                        <li style="margin-bottom: 15px; display: flex; gap: 10px;">
                            <span style="color: var(--primary-green); font-size: 20px;">✓</span>
                            <span>Add detailed descriptions</span>
                        </li>
                        <li style="margin-bottom: 15px; display: flex; gap: 10px;">
                            <span style="color: var(--primary-green); font-size: 20px;">✓</span>
                            <span>Hours are instantly confirmed</span>
                        </li>
                        <li style="margin-bottom: 15px; display: flex; gap: 10px;">
                            <span style="color: var(--primary-green); font-size: 20px;">✓</span>
                            <span>Check events page for opportunities</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Not Logged In View -->
        <div style="max-width: 800px; margin: 50px auto; padding: 0 20px;">
            <div class="login-prompt">
                <h3>🌟 Join Our Volunteer Community</h3>
                <p style="color: #666; font-size: 18px;">Track your impact, earn recognition, and connect with other volunteers.</p>
                
                <div class="feature-grid">
                    <div class="feature-item">
                        <div class="feature-icon">⏰</div>
                        <h4>Track Hours</h4>
                        <p style="color: #666;">Log your volunteer time easily</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🏆</div>
                        <h4>Leaderboard</h4>
                        <p style="color: #666;">See top volunteers</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🌱</div>
                        <h4>Make Impact</h4>
                        <p style="color: #666;">Track your contribution</p>
                    </div>
                </div>
                
                <div class="login-buttons">
                    <a href="login.php" class="login-btn">Login</a>
                    <a href="register.php" class="register-large">Register Now</a>
                </div>
            </div>
            
            <!-- Preview Leaderboard -->
            <div class="card" style="margin-top: 30px;">
                <h2>🏆 Top Volunteers</h2>
                <?php 
                $rank = 1;
                if(mysqli_num_rows($leaderboard_result) > 0): 
                    while($vol = mysqli_fetch_assoc($leaderboard_result)): 
                        if($rank <= 5):
                ?>
                    <div class="leaderboard-item">
                        <div class="rank rank-<?php echo $rank; ?>"><?php echo $rank; ?></div>
                        <div class="volunteer-info">
                            <div class="volunteer-name">
                                <?php echo $vol['first_name'] . ' ' . $vol['last_name']; ?>
                            </div>
                        </div>
                        <div class="volunteer-hours"><?php echo number_format($vol['total_hours'], 1); ?> hrs</div>
                    </div>
                <?php 
                        endif;
                        $rank++;
                    endwhile; 
                else: 
                ?>
                    <p style="text-align: center; color: #666; padding: 20px;">No volunteers yet</p>
                <?php endif; ?>
                <p style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                    <a href="register.php" style="color: var(--primary-green); font-weight: 600;">Register to see full leaderboard →</a>
                </p>
            </div>
        </div>
    <?php endif; ?>

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

    <script>
        // Form validation
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const hours = document.getElementById('hours')?.value;
            if (hours && hours <= 0) {
                e.preventDefault();
                alert('Please enter a valid number of hours.');
            }
        });
    </script>
</body>
</html>