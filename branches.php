<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Earth NGO | Branches</title>
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
            background: linear-gradient(rgba(46,125,50,0.9), rgba(46,125,50,0.9)), url('https://images.unsplash.com/photo-1441974231531-c6227db76b6e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
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
        }
        
        .page-header p {
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto;
        }
        
        /* Global Presence Section */
        .global-presence {
            padding: 80px 5%;
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
            background: linear-gradient(to right, var(--primary-green), var(--accent-brown));
            border-radius: 2px;
        }
        
        .map-container {
            max-width: 1200px;
            margin: 40px auto;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .map-container img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .stats-brief {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        
        .stat-brief-item {
            text-align: center;
        }
        
        .stat-brief-item h3 {
            font-size: 42px;
            color: var(--primary-green);
        }
        
        .stat-brief-item p {
            font-size: 18px;
            color: var(--accent-brown);
            font-weight: 500;
        }
        
        /* Branches Grid */
        .branches-section {
            padding: 80px 5%;
            background-color: var(--light-gray);
        }
        
        .branches-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .branch-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .branch-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
        }
        
        .branch-image {
            height: 220px;
            overflow: hidden;
        }
        
        .branch-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .branch-card:hover .branch-image img {
            transform: scale(1.1);
        }
        
        .branch-info {
            padding: 30px;
        }
        
        .branch-info h3 {
            color: var(--primary-green);
            font-size: 26px;
            margin-bottom: 10px;
        }
        
        .branch-country {
            color: var(--accent-brown);
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .branch-detail {
            margin: 10px 0;
            color: #555;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .branch-detail i {
            width: 20px;
            color: var(--primary-green);
            font-weight: bold;
        }
        
        .branch-contact {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .branch-contact p {
            margin: 5px 0;
            color: #666;
        }
        
        /* Regional Offices */
        .regional-section {
            padding: 80px 5%;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }
        
        .regional-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 40px auto 0;
        }
        
        .regional-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .regional-card:hover {
            transform: translateY(-5px);
        }
        
        .regional-icon {
            width: 80px;
            height: 80px;
            background: var(--secondary-cream);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 36px;
            color: var(--primary-green);
        }
        
        .regional-card h3 {
            color: var(--primary-green);
            margin-bottom: 10px;
        }
        
        .regional-card p {
            color: #666;
        }
        
        /* Join Branch CTA */
        .join-branch {
            padding: 80px 5%;
            background: linear-gradient(rgba(46,125,50,0.95), rgba(46,125,50,0.95)), url('https://images.unsplash.com/photo-1559027615-cd4628902d4a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            text-align: center;
            color: white;
        }
        
        .join-branch h2 {
            font-size: 42px;
            margin-bottom: 20px;
        }
        
        .join-branch p {
            font-size: 20px;
            max-width: 800px;
            margin: 0 auto 40px;
            opacity: 0.95;
        }
        
        .join-button {
            background-color: var(--accent-brown);
            color: white;
            padding: 18px 50px;
            border-radius: 60px;
            text-decoration: none;
            font-weight: 600;
            font-size: 20px;
            display: inline-block;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        
        .join-button:hover {
            background-color: #6b431f;
            transform: scale(1.05);
            border-color: white;
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
            <h1>Our Global Branches</h1>
            <p>Serving communities across 6 continents with 45+ regional offices</p>
        </div>
    </section>

    <!-- Global Presence -->
    <section class="global-presence">
        <h2 class="section-title">Global Presence</h2>
        <div class="map-container">
            <img src="https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1933&q=80" alt="World map showing branch locations">
        </div>
        <div class="stats-brief">
            <div class="stat-brief-item">
                <h3>45+</h3>
                <p>Countries</p>
            </div>
            <div class="stat-brief-item">
                <h3>128</h3>
                <p>Branch Offices</p>
            </div>
            <div class="stat-brief-item">
                <h3>6</h3>
                <p>Continents</p>
            </div>
            <div class="stat-brief-item">
                <h3>5000+</h3>
                <p>Local Staff</p>
            </div>
        </div>
    </section>

    <!-- Branches Grid -->
    <section class="branches-section">
        <h2 class="section-title">Regional Headquarters</h2>
        <div class="branches-grid">
            <!-- North America -->
            <div class="branch-card">
                <div class="branch-image">
                    <img src="https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="New York skyline">
                </div>
                <div class="branch-info">
                    <h3>North America</h3>
                    <div class="branch-country">🇺🇸 New York, USA</div>
                    <div class="branch-detail"><i>📍</i> 123 Green Avenue, Manhattan, NY 10001</div>
                    <div class="branch-detail"><i>📞</i> +1 (212) 555-7890</div>
                    <div class="branch-detail"><i>✉️</i> nyoffice@greenearthngo.org</div>
                    <div class="branch-contact">
                        <p><strong>Regional Director:</strong> Sarah Johnson</p>
                        <p><strong>Founded:</strong> 2010</p>
                    </div>
                </div>
            </div>
            
            <!-- South America -->
            <div class="branch-card">
                <div class="branch-image">
                    <img src="https://images.unsplash.com/photo-1483729558449-99ef09a8c325?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="São Paulo cityscape">
                </div>
                <div class="branch-info">
                    <h3>South America</h3>
                    <div class="branch-country">🇧🇷 São Paulo, Brazil</div>
                    <div class="branch-detail"><i>📍</i> Av. Paulista 1500, São Paulo, SP 01310-100</div>
                    <div class="branch-detail"><i>📞</i> +55 (11) 3456-7890</div>
                    <div class="branch-detail"><i>✉️</i> brasil@greenearthngo.org</div>
                    <div class="branch-contact">
                        <p><strong>Regional Director:</strong> Carlos Mendes</p>
                        <p><strong>Founded:</strong> 2012</p>
                    </div>
                </div>
            </div>
            
            <!-- Europe -->
            <div class="branch-card">
                <div class="branch-image">
                    <img src="https://images.unsplash.com/photo-1467269204594-9661b134dd2b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="London cityscape">
                </div>
                <div class="branch-info">
                    <h3>Europe</h3>
                    <div class="branch-country">🇬🇧 London, UK</div>
                    <div class="branch-detail"><i>📍</i> 45 Green Park Road, London, SW1A 1AA</div>
                    <div class="branch-detail"><i>📞</i> +44 (20) 7946-0123</div>
                    <div class="branch-detail"><i>✉️</i> europe@greenearthngo.org</div>
                    <div class="branch-contact">
                        <p><strong>Regional Director:</strong> Emma Watson</p>
                        <p><strong>Founded:</strong> 2011</p>
                    </div>
                </div>
            </div>
            
            <!-- Africa -->
            <div class="branch-card">
                <div class="branch-image">
                    <img src="https://images.unsplash.com/photo-1523805009345-7448845a9e53?ixlib=rb-4.0.3&auto=format&fit=crop&w=1952&q=80" alt="Nairobi cityscape">
                </div>
                <div class="branch-info">
                    <h3>Africa</h3>
                    <div class="branch-country">🇰🇪 Nairobi, Kenya</div>
                    <div class="branch-detail"><i>📍</i> 78 UN Avenue, Gigiri, Nairobi</div>
                    <div class="branch-detail"><i>📞</i> +254 (20) 712-3456</div>
                    <div class="branch-detail"><i>✉️</i> africa@greenearthngo.org</div>
                    <div class="branch-contact">
                        <p><strong>Regional Director:</strong> James Omondi</p>
                        <p><strong>Founded:</strong> 2013</p>
                    </div>
                </div>
            </div>
            
            <!-- Asia -->
            <div class="branch-card">
                <div class="branch-image">
                    <img src="https://images.unsplash.com/photo-1557939403-1760a0e47505?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80" alt="Singapore skyline with modern architecture">
                </div>
                <div class="branch-info">
                    <h3>Asia</h3>
                    <div class="branch-country">🇸🇬 Singapore</div>
                    <div class="branch-detail"><i>📍</i> 10 Marina Boulevard, #23-01, Singapore 018983</div>
                    <div class="branch-detail"><i>📞</i> +65 6789-0123</div>
                    <div class="branch-detail"><i>✉️</i> asia@greenearthngo.org</div>
                    <div class="branch-contact">
                        <p><strong>Regional Director:</strong> Li Wei Chen</p>
                        <p><strong>Founded:</strong> 2014</p>
                    </div>
                </div>
            </div>
            
            <!-- Australia -->
            <div class="branch-card">
                <div class="branch-image">
                    <img src="https://images.unsplash.com/photo-1523482580672-f109ba8cb9be?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="Sydney skyline">
                </div>
                <div class="branch-info">
                    <h3>Oceania</h3>
                    <div class="branch-country">🇦🇺 Sydney, Australia</div>
                    <div class="branch-detail"><i>📍</i> 45 Harbour Street, Sydney, NSW 2000</div>
                    <div class="branch-detail"><i>📞</i> +61 (2) 9876-5432</div>
                    <div class="branch-detail"><i>✉️</i> oceania@greenearthngo.org</div>
                    <div class="branch-contact">
                        <p><strong>Regional Director:</strong> Michael Thompson</p>
                        <p><strong>Founded:</strong> 2015</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Regional Offices -->
    <section class="regional-section">
        <h2 class="section-title">Other Key Locations</h2>
        <div class="regional-grid">
            <div class="regional-card">
                <div class="regional-icon">🇮🇳</div>
                <h3>India</h3>
                <p>New Delhi, Mumbai, Bangalore</p>
                <p style="margin-top: 10px; color: var(--primary-green);">+91 11 2345 6789</p>
            </div>
            <div class="regional-card">
                <div class="regional-icon">🇨🇳</div>
                <h3>China</h3>
                <p>Beijing, Shanghai, Guangzhou</p>
                <p style="margin-top: 10px; color: var(--primary-green);">+86 10 8765 4321</p>
            </div>
            <div class="regional-card">
                <div class="regional-icon">🇧🇷</div>
                <h3>Brazil</h3>
                <p>Rio de Janeiro, Brasília</p>
                <p style="margin-top: 10px; color: var(--primary-green);">+55 21 3456 7890</p>
            </div>
            <div class="regional-card">
                <div class="regional-icon">🇿🇦</div>
                <h3>South Africa</h3>
                <p>Cape Town, Johannesburg</p>
                <p style="margin-top: 10px; color: var(--primary-green);">+27 21 789 0123</p>
            </div>
            <div class="regional-card">
                <div class="regional-icon">🇩🇪</div>
                <h3>Germany</h3>
                <p>Berlin, Munich, Frankfurt</p>
                <p style="margin-top: 10px; color: var(--primary-green);">+49 30 1234 5678</p>
            </div>
            <div class="regional-card">
                <div class="regional-icon">🇯🇵</div>
                <h3>Japan</h3>
                <p>Tokyo, Osaka, Kyoto</p>
                <p style="margin-top: 10px; color: var(--primary-green);">+81 3 3456 7890</p>
            </div>
        </div>
    </section>

    <!-- Join Branch CTA -->
    <section class="join-branch">
        <h2>Start a Branch in Your City</h2>
        <p>Passionate about environmental conservation? We're always looking for dedicated individuals to establish new branches and expand our impact.</p>
        <a href="contact.php" class="join-button">Contact Us to Start a Branch</a>
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