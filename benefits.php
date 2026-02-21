<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Earth NGO | Benefits</title>
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
            background: linear-gradient(rgba(46,125,50,0.85), rgba(46,125,50,0.85)), 
            url('https://images.unsplash.com/photo-1559027615-cd4628902d4a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
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
        
        /* Benefits Section */
        .benefits-main {
            padding: 80px 5%;
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
        
        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            max-width: 1300px;
            margin: 0 auto;
        }
        
        .benefit-card {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        
        .benefit-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .benefit-icon {
            width: 90px;
            height: 90px;
            background: var(--secondary-cream);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 40px;
            color: var(--primary-green);
            border: 3px solid var(--primary-green);
            transition: all 0.3s;
        }
        
        .benefit-card:hover .benefit-icon {
            background: var(--primary-green);
            color: white;
            transform: rotate(360deg);
        }
        
        .benefit-card h3 {
            color: var(--primary-green);
            font-size: 24px;
            margin-bottom: 15px;
        }
        
        .benefit-card p {
            color: #666;
            line-height: 1.7;
        }
        
        /* Why Join Section */
        .why-join {
            background-color: var(--secondary-cream);
            padding: 80px 5%;
        }
        
        .why-content {
            max-width: 1300px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }
        
        .why-text h2 {
            color: var(--primary-green);
            font-size: 36px;
            margin-bottom: 25px;
        }
        
        .why-text ul {
            list-style: none;
        }
        
        .why-text li {
            margin-bottom: 15px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .why-text li::before {
            content: "✓";
            color: var(--primary-green);
            font-weight: bold;
            font-size: 22px;
        }
        
        .why-image {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            position: relative;
        }
        
        .why-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s;
        }
        
        .why-image:hover img {
            transform: scale(1.03);
        }
        
        .image-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: 500;
            transform: translateY(100%);
            transition: transform 0.3s;
        }
        
        .why-image:hover .image-caption {
            transform: translateY(0);
        }
        
        /* Stats Section */
        .stats-section {
            padding: 80px 5%;
            background: linear-gradient(135deg, #1e3c2c, #2e7d32);
            color: white;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            max-width: 1300px;
            margin: 0 auto;
            text-align: center;
        }
        
        .stat-item h3 {
            font-size: 48px;
            margin-bottom: 10px;
        }
        
        .stat-item p {
            font-size: 18px;
            opacity: 0.9;
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
            <h1>Benefits of Joining</h1>
            <p>Discover how being part of Green Earth NGO enriches your life and community</p>
        </div>
    </section>

    <!-- Benefits Grid -->
    <section class="benefits-main">
        <h2 class="section-title">Why Become a Member?</h2>
        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">🌱</div>
                <h3>Personal Growth</h3>
                <p>Develop leadership skills, project management abilities, and environmental expertise through hands-on experience and training programs.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">🤝</div>
                <h3>Community Connection</h3>
                <p>Build meaningful relationships with like-minded individuals who share your passion for environmental conservation and social change.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">🌍</div>
                <h3>Global Impact</h3>
                <p>Contribute to worldwide environmental goals including climate action, biodiversity protection, and sustainable development.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">📚</div>
                <h3>Learning Opportunities</h3>
                <p>Access workshops, seminars, and certification programs conducted by environmental experts and research institutions.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">🏆</div>
                <h3>Recognition</h3>
                <p>Receive certificates, awards, and recommendations that enhance your professional portfolio and career prospects.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">💚</div>
                <h3>Health & Wellness</h3>
                <p>Spend time in nature, engage in outdoor activities, and experience the mental and physical benefits of environmental work.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">🎓</div>
                <h3>Career Development</h3>
                <p>Gain valuable experience for careers in environmental science, sustainability, policy-making, and non-profit management.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">🔬</div>
                <h3>Research Access</h3>
                <p>Participate in field research projects and contribute to scientific studies on climate change and biodiversity.</p>
            </div>
        </div>
    </section>

    <!-- Why Join Section -->
    <section class="why-join">
        <div class="why-content">
            <div class="why-text">
                <h2>Why Join Green Earth?</h2>
                <ul>
                    <li>Make a tangible difference in environmental conservation</li>
                    <li>Network with environmental professionals and activists</li>
                    <li>Access exclusive training and skill-building workshops</li>
                    <li>Participate in international conferences and events</li>
                    <li>Receive mentorship from experienced conservationists</li>
                    <li>Get priority registration for all our programs</li>
                    <li>Be part of a global movement for positive change</li>
                </ul>
            </div>
            <div class="why-image">
                <img src="https://images.unsplash.com/photo-1573164574472-797cdf4a583a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80" alt="Diverse group of volunteers planting trees together">
                <div class="image-caption">🌱 Making a difference together</div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <h3>8,500+</h3>
                <p>Active Members</p>
            </div>
            <div class="stat-item">
                <h3>45+</h3>
                <p>Countries</p>
            </div>
            <div class="stat-item">
                <h3>120+</h3>
                <p>Partner Organizations</p>
            </div>
            <div class="stat-item">
                <h3>15+</h3>
                <p>Years of Impact</p>
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