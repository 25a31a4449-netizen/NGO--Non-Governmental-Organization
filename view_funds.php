<?php
require_once 'config.php';

// Get total funds
$funds_query = "SELECT total_funds, monthly_goal, yearly_goal FROM funds_tracking WHERE id = 1";
$funds_result = mysqli_query($conn, $funds_query);
$funds_data = mysqli_fetch_assoc($funds_result);
$total_funds = $funds_data['total_funds'] ?? 0;
$monthly_goal = $funds_data['monthly_goal'] ?? 10000;
$yearly_goal = $funds_data['yearly_goal'] ?? 120000;

// Calculate monthly progress
$monthly_progress = min(($total_funds / $monthly_goal) * 100, 100);
$yearly_progress = min(($total_funds / $yearly_goal) * 100, 100);

// Get all donations with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$donations_query = "SELECT * FROM donations ORDER BY donation_date DESC LIMIT $offset, $limit";
$donations_result = mysqli_query($conn, $donations_query);

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM donations";
$count_result = mysqli_query($conn, $count_query);
$total_donations = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_donations / $limit);

// Get top donors
$top_donors_query = "SELECT donor_name, SUM(amount) as total, COUNT(*) as count 
                     FROM donations WHERE is_anonymous = 0 
                     GROUP BY donor_name ORDER BY total DESC LIMIT 5";
$top_donors_result = mysqli_query($conn, $top_donors_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Earth NGO | View Funds</title>
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
            background: linear-gradient(rgba(255,215,0,0.9), rgba(46,125,50,0.9)), 
            url('https://images.unsplash.com/photo-1554224154-26032dfc0d6c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2026&q=80');
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
        
        /* Main Content */
        .container {
            max-width: 1300px;
            margin: 50px auto;
            padding: 0 20px;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card h3 {
            color: var(--primary-green);
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .stat-card .stat-value {
            font-size: 42px;
            font-weight: 700;
            color: var(--donate-gold);
            margin-bottom: 10px;
        }
        
        /* Progress Bars */
        .progress-container {
            margin: 20px 0;
        }
        
        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            color: #666;
            font-size: 14px;
        }
        
        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--donate-gold), var(--primary-green));
            border-radius: 10px;
            transition: width 0.5s ease;
        }
        
        /* Top Donors */
        .top-donors {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .top-donors h2 {
            color: var(--primary-green);
            margin-bottom: 20px;
        }
        
        .donor-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .donor-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: var(--light-gray);
            border-radius: 10px;
        }
        
        .donor-rank {
            width: 40px;
            height: 40px;
            background: var(--donate-gold);
            color: var(--text-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
        
        .donor-info {
            flex: 1;
        }
        
        .donor-name {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .donor-amount {
            color: var(--primary-green);
            font-weight: 700;
        }
        
        /* Donations Table */
        .donations-table {
            width: 100%;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
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
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }
        
        .pagination a {
            padding: 10px 15px;
            background-color: white;
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .pagination a:hover,
        .pagination a.active {
            background-color: var(--primary-green);
            color: white;
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
        
        @media (max-width: 768px) {
            .donor-list {
                grid-template-columns: 1fr;
            }
            
            .donations-table {
                font-size: 14px;
            }
            
            .donations-table td,
            .donations-table th {
                padding: 10px;
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

    <!-- Page Header -->
    <section class="page-header">
        <div>
            <h1>Funds Overview</h1>
            <p>See how your donations are making a difference</p>
        </div>
    </section>

    <div class="container">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Funds Raised</h3>
                <div class="stat-value">$<?php echo number_format($total_funds, 2); ?></div>
                
                <!-- Monthly Progress -->
                <div class="progress-container">
                    <div class="progress-label">
                        <span>Monthly Goal ($<?php echo number_format($monthly_goal); ?>)</span>
                        <span><?php echo number_format($monthly_progress, 1); ?>%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo $monthly_progress; ?>%;"></div>
                    </div>
                </div>
                
                <!-- Yearly Progress -->
                <div class="progress-container">
                    <div class="progress-label">
                        <span>Yearly Goal ($<?php echo number_format($yearly_goal); ?>)</span>
                        <span><?php echo number_format($yearly_progress, 1); ?>%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo $yearly_progress; ?>%;"></div>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <h3>Total Donations</h3>
                <div class="stat-value"><?php echo $total_donations; ?></div>
                <p style="color: #666;">Individual contributions</p>
            </div>
            
            <div class="stat-card">
                <h3>Average Donation</h3>
                <div class="stat-value">
                    $<?php echo $total_donations > 0 ? number_format($total_funds / $total_donations, 2) : '0.00'; ?>
                </div>
                <p style="color: #666;">Per contribution</p>
            </div>
        </div>

        <!-- Top Donors -->
        <?php if(mysqli_num_rows($top_donors_result) > 0): ?>
        <div class="top-donors">
            <h2>🏆 Top Donors</h2>
            <div class="donor-list">
                <?php 
                $rank = 1;
                while($donor = mysqli_fetch_assoc($top_donors_result)): 
                ?>
                <div class="donor-item">
                    <div class="donor-rank"><?php echo $rank; ?></div>
                    <div class="donor-info">
                        <div class="donor-name"><?php echo htmlspecialchars($donor['donor_name']); ?></div>
                        <div class="donor-amount">$<?php echo number_format($donor['total'], 2); ?></div>
                        <small><?php echo $donor['count']; ?> donation<?php echo $donor['count'] > 1 ? 's' : ''; ?></small>
                    </div>
                </div>
                <?php 
                $rank++;
                endwhile; 
                ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- All Donations Table -->
        <h2 style="color: var(--primary-green); margin: 40px 0 20px;">All Donations</h2>
        
        <table class="donations-table">
            <thead>
                <tr>
                    <th>Donor</th>
                    <th>Email</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($donations_result) > 0): ?>
                    <?php while($donation = mysqli_fetch_assoc($donations_result)): ?>
                        <tr>
                            <td>
                                <?php if($donation['is_anonymous']): ?>
                                    <span class="anonymous">Anonymous</span>
                                <?php else: ?>
                                    <?php echo htmlspecialchars($donation['donor_name']); ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $donation['is_anonymous'] ? 'Hidden' : htmlspecialchars($donation['donor_email']); ?></td>
                            <td><strong>$<?php echo number_format($donation['amount'], 2); ?></strong></td>
                            <td><?php echo ucfirst(str_replace('_', ' ', $donation['payment_method'])); ?></td>
                            <td><?php echo date('M j, Y g:i A', strtotime($donation['donation_date'])); ?></td>
                            <td><span style="color: #4CAF50;">✓ <?php echo ucfirst($donation['status']); ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px;">No donations yet. Be the first!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
            <div class="pagination">
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>

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