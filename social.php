<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Earth NGO | Social Services</title>
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
            --social-blue: #3498db;
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
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
            url('https://images.pexels.com/photos/6646918/pexels-photo-6646918.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');
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
            background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.4) 100%);
            pointer-events: none;
        }
        
        .page-header div {
            position: relative;
            z-index: 2;
        }
        
        .page-header h1 {
            font-size: 64px;
            margin-bottom: 20px;
            text-shadow: 3px 3px 12px rgba(0,0,0,0.7);
            font-weight: 800;
            animation: fadeInUp 1s ease;
        }
        
        .page-header p {
            font-size: 24px;
            max-width: 800px;
            margin: 0 auto;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
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
        
        /* Stats Section */
        .stats-section {
            display: flex;
            justify-content: space-around;
            padding: 80px 5%;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
            url('https://images.unsplash.com/photo-1593113598335-c288c59f9681?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            flex-wrap: wrap;
            gap: 40px;
            position: relative;
        }
        
        .stats-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.3) 100%);
            pointer-events: none;
        }
        
        .stat-item {
            text-align: center;
            position: relative;
            z-index: 2;
        }
        
        .stat-item h3 {
            font-size: 56px;
            margin-bottom: 10px;
            font-weight: 800;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
        }
        
        .stat-item p {
            font-size: 20px;
            opacity: 0.95;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
        }
        
        /* Programs Section */
        .programs-section {
            padding: 80px 5%;
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), 
            url('https://images.unsplash.com/photo-1573164574472-797cdf4a583a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            position: relative;
        }
        
        .programs-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.3) 100%);
            pointer-events: none;
        }
        
        .programs-section .section-title {
            color: white;
            position: relative;
            z-index: 2;
        }
        
        .programs-section .section-title::after {
            background: linear-gradient(to right, white, var(--secondary-cream));
        }
        
        .programs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 40px auto 0;
            position: relative;
            z-index: 2;
        }
        
        .program-item {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            transition: transform 0.3s;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .program-item:hover {
            transform: translateY(-10px);
            background: rgba(255,255,255,0.25);
            border-color: white;
        }
        
        .program-icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
            color: white;
            border: 2px solid white;
        }
        
        .program-item h3 {
            color: white;
            margin-bottom: 15px;
            font-size: 24px;
        }
        
        .program-item p {
            color: rgba(255,255,255,0.9);
            line-height: 1.6;
        }
        
        /* CTA Section */
        .cta-section {
            padding: 100px 5%;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
            url('https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.4) 100%);
            pointer-events: none;
        }
        
        .cta-section h2 {
            font-size: 48px;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
        }
        
        .cta-section p {
            font-size: 22px;
            max-width: 800px;
            margin: 0 auto 40px;
            position: relative;
            z-index: 2;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
        }
        
        .cta-button {
            background-color: var(--register-orange);
            color: white;
            padding: 18px 50px;
            border-radius: 60px;
            text-decoration: none;
            font-weight: 700;
            font-size: 22px;
            display: inline-block;
            transition: all 0.3s;
            border: 2px solid transparent;
            position: relative;
            z-index: 2;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }
        
        .cta-button:hover {
            background-color: #d35400;
            transform: scale(1.05) translateY(-3px);
            border-color: white;
            box-shadow: 0 15px 30px rgba(0,0,0,0.4);
        }
        
        .header-image-section {
            padding: 0 5% 40px;
            background-color: white;
        }
        
        .header-image-container {
            max-width: 1300px;
            margin: 0 auto;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .header-image-container img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s;
        }
        
        .header-image-container:hover img {
            transform: scale(1.02);
        }
        
        .intro-section {
            padding: 20px 5% 40px;
            text-align: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .intro-section h2 {
            color: var(--primary-green);
            font-size: 42px;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 20px;
        }
        
        .intro-section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(to right, var(--primary-green), var(--social-blue));
            border-radius: 2px;
        }
        
        .intro-section p {
            font-size: 18px;
            line-height: 1.8;
            color: #666;
            max-width: 900px;
            margin: 0 auto;
        }
        
        .services-section {
            padding: 80px 5%;
            background-color: var(--light-gray);
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
            background: linear-gradient(to right, var(--primary-green), var(--social-blue));
            border-radius: 2px;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            max-width: 1300px;
            margin: 0 auto;
        }
        
        .service-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .service-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
        }
        
        .service-image {
            height: 220px;
            overflow: hidden;
        }
        
        .service-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .service-card:hover .service-image img {
            transform: scale(1.1);
        }
        
        .service-content {
            padding: 30px;
        }
        
        .service-icon {
            width: 70px;
            height: 70px;
            background: var(--secondary-cream);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: -65px auto 20px;
            font-size: 36px;
            color: var(--primary-green);
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .service-content h3 {
            color: var(--primary-green);
            font-size: 24px;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .service-content p {
            color: #666;
            line-height: 1.7;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .service-stats {
            display: flex;
            justify-content: space-around;
            padding: 15px 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }
        
        .service-stat {
            text-align: center;
        }
        
        .service-stat span {
            display: block;
            font-size: 20px;
            font-weight: 700;
            color: var(--social-blue);
        }
        
        .service-stat small {
            color: #999;
            font-size: 12px;
        }
        
        .service-link {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: color 0.3s;
        }
        
        .service-link:hover {
            color: var(--social-blue);
        }
        
        .testimonials-section {
            padding: 80px 5%;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }
        
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 40px auto 0;
        }
        
        .testimonial-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            position: relative;
        }
        
        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 60px;
            color: var(--social-blue);
            opacity: 0.2;
            font-family: serif;
        }
        
        .testimonial-card p {
            color: #666;
            line-height: 1.8;
            margin-bottom: 20px;
            font-style: italic;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .testimonial-author img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .author-info h4 {
            color: var(--primary-green);
            font-size: 16px;
        }
        
        .author-info p {
            color: #999;
            font-size: 14px;
            margin-bottom: 0;
        }
        
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
            <h1>Social Services</h1>
            <p>Empowering communities through sustainable social development programs</p>
        </div>
    </section>

    <!-- Introduction -->
    <section class="intro-section">
        <h2>Our Social Impact</h2>
        <p>At Green Earth NGO, we believe that environmental conservation and social development go hand in hand. Our social services programs focus on empowering marginalized communities, providing education, healthcare access, and sustainable livelihoods while promoting environmental stewardship.</p>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="stat-item">
            <h3>50,000+</h3>
            <p>Lives Impacted</p>
        </div>
        <div class="stat-item">
            <h3>120+</h3>
            <p>Communities Served</p>
        </div>
        <div class="stat-item">
            <h3>25+</h3>
            <p>Social Programs</p>
        </div>
        <div class="stat-item">
            <h3>5,000+</h3>
            <p>Families Supported</p>
        </div>
    </section>

    <!-- Social Services Grid -->
    <section class="services-section">
        <h2 class="section-title">Our Social Services</h2>
        <div class="services-grid">
            <!-- Education -->
            <div class="service-card">
                <div class="service-image">
                    <img src="https://images.pexels.com/photos/301926/pexels-photo-301926.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Children in classroom">
                </div>
                <div class="service-content">
                    <div class="service-icon">📚</div>
                    <h3>Education for All</h3>
                    <p>Providing quality education, school supplies, and scholarships to underprivileged children in rural and urban areas.</p>
                    <div class="service-stats">
                        <div class="service-stat">
                            <span>5,000+</span>
                            <small>Children</small>
                        </div>
                        <div class="service-stat">
                            <span>50+</span>
                            <small>Schools</small>
                        </div>
                    </div>
                    <a href="#" class="service-link">Learn More →</a>
                </div>
            </div>
            
            <!-- Healthcare -->
            <div class="service-card">
                <div class="service-image">
                    <img src="https://images.unsplash.com/photo-1631815589968-fdb09a223b1e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80" alt="Medical camp">
                </div>
                <div class="service-content">
                    <div class="service-icon">🏥</div>
                    <h3>Healthcare Access</h3>
                    <p>Organizing medical camps, providing basic healthcare services, and promoting preventive health awareness in remote communities.</p>
                    <div class="service-stats">
                        <div class="service-stat">
                            <span>15,000+</span>
                            <small>Patients</small>
                        </div>
                        <div class="service-stat">
                            <span>100+</span>
                            <small>Camps</small>
                        </div>
                    </div>
                    <a href="#" class="service-link">Learn More →</a>
                </div>
            </div>
            
            <!-- Women Empowerment -->
            <div class="service-card">
                <div class="service-image">
                    <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Women working together">
                </div>
                <div class="service-content">
                    <div class="service-icon">👩‍👧‍👦</div>
                    <h3>Women Empowerment</h3>
                    <p>Supporting women through skill development, micro-finance initiatives, and entrepreneurship programs for financial independence.</p>
                    <div class="service-stats">
                        <div class="service-stat">
                            <span>3,000+</span>
                            <small>Women</small>
                        </div>
                        <div class="service-stat">
                            <span>200+</span>
                            <small>Businesses</small>
                        </div>
                    </div>
                    <a href="#" class="service-link">Learn More →</a>
                </div>
            </div>
            
            <!-- Food Security -->
            <div class="service-card">
                <div class="service-image">
                    <img src="https://images.pexels.com/photos/6995283/pexels-photo-6995283.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Food distribution">
                </div>
                <div class="service-content">
                    <div class="service-icon">🍲</div>
                    <h3>Food Security</h3>
                    <p>Running community kitchens, food banks, and nutrition programs for vulnerable populations including children and elderly.</p>
                    <div class="service-stats">
                        <div class="service-stat">
                            <span>100,000+</span>
                            <small>Meals</small>
                        </div>
                        <div class="service-stat">
                            <span>30+</span>
                            <small>Centers</small>
                        </div>
                    </div>
                    <a href="#" class="service-link">Learn More →</a>
                </div>
            </div>
            
            <!-- Livelihood -->
            <div class="service-card">
                <div class="service-image">
                    <img src="https://images.unsplash.com/photo-1606787366850-de6330128bfc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Vocational training">
                </div>
                <div class="service-content">
                    <div class="service-icon">🔧</div>
                    <h3>Livelihood Programs</h3>
                    <p>Vocational training and job placement assistance for youth and marginalized groups to create sustainable income opportunities.</p>
                    <div class="service-stats">
                        <div class="service-stat">
                            <span>2,500+</span>
                            <small>Trained</small>
                        </div>
                        <div class="service-stat">
                            <span>70%</span>
                            <small>Placement</small>
                        </div>
                    </div>
                    <a href="#" class="service-link">Learn More →</a>
                </div>
            </div>
            
            <!-- Child Protection -->
            <div class="service-card">
                <div class="service-image">
                    <img src="https://images.unsplash.com/photo-1485546246426-74dc88dec4d9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Children playing">
                </div>
                <div class="service-content">
                    <div class="service-icon">🧸</div>
                    <h3>Child Protection</h3>
                    <p>Creating safe spaces, counseling services, and protection programs for vulnerable children and orphans.</p>
                    <div class="service-stats">
                        <div class="service-stat">
                            <span>1,200+</span>
                            <small>Children</small>
                        </div>
                        <div class="service-stat">
                            <span>15+</span>
                            <small>Centers</small>
                        </div>
                    </div>
                    <a href="#" class="service-link">Learn More →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section class="programs-section">
        <h2 class="section-title">Ongoing Programs</h2>
        <div class="programs-grid">
            <div class="program-item">
                <div class="program-icon">📖</div>
                <h3>After-School Tutoring</h3>
                <p>Free tutoring and mentoring for children from low-income families to improve academic performance.</p>
            </div>
            <div class="program-item">
                <div class="program-icon">💉</div>
                <h3>Vaccination Drives</h3>
                <p>Partnering with health departments to ensure children and elderly receive essential vaccinations.</p>
            </div>
            <div class="program-item">
                <div class="program-icon">🧵</div>
                <h3>Self-Help Groups</h3>
                <p>Facilitating women's self-help groups for savings, credit, and small business development.</p>
            </div>
            <div class="program-item">
                <div class="program-icon">🌾</div>
                <h3>Community Farming</h3>
                <p>Promoting sustainable agriculture and community gardens for food security.</p>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials-section">
        <h2 class="section-title">Stories of Change</h2>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <p>"The education program changed my daughter's life. She now goes to school with confidence and dreams of becoming a teacher."</p>
                <div class="testimonial-author">
                    <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Lakshmi">
                    <div class="author-info">
                        <h4>Lakshmi</h4>
                        <p>Mother, Chennai</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <p>"Through the women's empowerment program, I started my own tailoring business. Now I employ three other women from my village."</p>
                <div class="testimonial-author">
                    <img src="https://images.unsplash.com/photo-1531123897727-8f129e1688ce?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Fatima">
                    <div class="author-info">
                        <h4>Fatima</h4>
                        <p>Entrepreneur, Hyderabad</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <p>"The medical camp saved my father's life. They detected his condition early and arranged for treatment we couldn't afford."</p>
                <div class="testimonial-author">
                    <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Raju">
                    <div class="author-info">
                        <h4>Raju</h4>
                        <p>Son, Rural Karnataka</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2>Support Our Social Initiatives</h2>
        <p>Your contribution can help us reach more communities and create lasting social impact</p>
        <a href="register.php" class="cta-button">Get Involved</a>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Green Earth NGO</h3>
                <p>Non-governmental organization dedicated to environmental conservation and social development since 2010.</p>
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