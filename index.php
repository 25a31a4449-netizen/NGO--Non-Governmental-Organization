<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Earth NGO | Home</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Color Palette */
        :root {
            --primary-green: #2e7d32;
            --secondary-cream: #f5f5dc;
            --accent-brown: #8b5a2b;
            --register-orange: #e67e22;
            --donate-gold: #ffd700;
            --text-dark: #2c3e50;
            --light-gray: #f4f4f4;
            --gold: #ffd700;
            --sponsor-gray: #f8f9fa;
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
        
        /* HERO SECTION */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
            url('https://images.unsplash.com/photo-1469474968028-56623f02e42e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 700px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.4) 100%);
            pointer-events: none;
        }
        
        .hero-content {
            max-width: 900px;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }
        
        .hero-content h1 {
            font-size: 64px;
            margin-bottom: 20px;
            text-shadow: 3px 3px 12px rgba(0,0,0,0.7);
            animation: fadeInUp 1s ease;
            font-weight: 800;
            line-height: 1.2;
        }
        
        .hero-content p {
            font-size: 24px;
            margin-bottom: 40px;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
            animation: fadeInUp 1s ease 0.2s both;
            font-weight: 400;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .cta-button {
            background-color: var(--primary-green);
            color: white;
            padding: 18px 50px;
            border-radius: 60px;
            text-decoration: none;
            font-weight: 700;
            font-size: 22px;
            display: inline-block;
            transition: all 0.3s;
            animation: fadeInUp 1s ease 0.4s both;
            border: 2px solid transparent;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }
        
        .cta-button:hover {
            background-color: #1b5e20;
            transform: scale(1.05) translateY(-3px);
            border-color: white;
            box-shadow: 0 15px 30px rgba(0,0,0,0.4);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Mission Section */
        .mission {
            padding: 100px 5%;
            background-color: white;
        }
        
        .section-title {
            text-align: center;
            font-size: 42px;
            color: var(--primary-green);
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 20px;
            font-weight: 800;
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
        
        .mission-content {
            display: flex;
            align-items: center;
            gap: 60px;
            max-width: 1300px;
            margin: 50px auto 0;
        }
        
        .mission-text {
            flex: 1;
        }
        
        .mission-text h3 {
            color: var(--accent-brown);
            font-size: 32px;
            margin-bottom: 25px;
        }
        
        .mission-text p {
            line-height: 1.9;
            font-size: 17px;
            color: #555;
            margin-bottom: 25px;
        }
        
        .mission-image {
            flex: 1;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .mission-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.5s;
        }
        
        .mission-image:hover img {
            transform: scale(1.05);
        }
        
        /* NEW SECTION 1: TOP HELPS */
        .top-helps {
            padding: 80px 5%;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }
        
        .helps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1300px;
            margin: 40px auto 0;
        }
        
        .help-card {
            background: white;
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .help-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-green), var(--accent-brown));
        }
        
        .help-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
        }
        
        .help-icon {
            width: 100px;
            height: 100px;
            background: var(--secondary-cream);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 48px;
            color: var(--primary-green);
            transition: all 0.3s;
        }
        
        .help-card:hover .help-icon {
            background: var(--primary-green);
            color: white;
            transform: rotate(360deg);
        }
        
        .help-card h3 {
            color: var(--primary-green);
            font-size: 24px;
            margin-bottom: 15px;
        }
        
        .help-card p {
            color: #666;
            line-height: 1.7;
            margin-bottom: 20px;
        }
        
        .help-stats {
            font-size: 28px;
            font-weight: 700;
            color: var(--accent-brown);
            margin-top: 15px;
        }
        
        .help-stats span {
            font-size: 16px;
            color: #999;
            font-weight: normal;
        }
        
        /* NEW SECTION 2: ACHIEVEMENTS */
        .achievements {
            padding: 80px 5%;
            background: linear-gradient(rgba(46,125,50,0.95), rgba(46,125,50,0.95)), 
            url('https://images.unsplash.com/photo-1552799446-159ba9523315?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
        }
        
        .achievements-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            max-width: 1300px;
            margin: 50px auto 0;
        }
        
        .achievement-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s;
        }
        
        .achievement-card:hover {
            transform: translateY(-10px);
            background: rgba(255,255,255,0.2);
            border-color: var(--gold);
        }
        
        .achievement-icon {
            font-size: 56px;
            margin-bottom: 20px;
        }
        
        .achievement-card h3 {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 10px;
            color: var(--gold);
        }
        
        .achievement-card p {
            font-size: 20px;
            opacity: 0.95;
            margin-bottom: 15px;
        }
        
        .achievement-year {
            font-size: 16px;
            color: var(--secondary-cream);
            font-style: italic;
        }
        
        /* NEW SECTION 3: SPONSORS */
        .sponsors {
            padding: 80px 5%;
            background-color: var(--sponsor-gray);
        }
        
        .sponsors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 40px;
            max-width: 1300px;
            margin: 50px auto 0;
            align-items: center;
        }
        
        .sponsor-item {
            background: white;
            padding: 30px 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.3s;
            filter: grayscale(100%);
            opacity: 0.7;
        }
        
        .sponsor-item:hover {
            transform: translateY(-5px);
            filter: grayscale(0);
            opacity: 1;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .sponsor-logo {
            width: 120px;
            height: 120px;
            background: var(--secondary-cream);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 48px;
            color: var(--primary-green);
        }
        
        .sponsor-item h4 {
            color: var(--text-dark);
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .sponsor-item p {
            color: #999;
            font-size: 14px;
        }
        
        /* Impact Numbers */
        .impact {
            display: flex;
            justify-content: space-around;
            padding: 80px 5%;
            background: linear-gradient(135deg, var(--primary-green), #1b5e20);
            color: white;
            flex-wrap: wrap;
            gap: 40px;
        }
        
        .impact-item {
            text-align: center;
            flex: 1;
            min-width: 200px;
        }
        
        .impact-item h2 {
            font-size: 56px;
            margin-bottom: 10px;
            font-weight: 800;
        }
        
        .impact-item p {
            font-size: 20px;
            opacity: 0.9;
        }
        
        /* Programs Section */
        .programs {
            padding: 100px 5%;
            background-color: var(--light-gray);
        }
        
        .program-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 35px;
            max-width: 1300px;
            margin: 50px auto 0;
        }
        
        .program-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .program-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .program-card img {
            width: 100%;
            height: 240px;
            object-fit: cover;
        }
        
        .program-card h3 {
            padding: 25px 25px 15px;
            color: var(--primary-green);
            font-size: 24px;
        }
        
        .program-card p {
            padding: 0 25px 25px;
            color: #666;
            line-height: 1.7;
        }
        
        /* Testimonials */
        .testimonials {
            padding: 100px 5%;
            background-color: var(--secondary-cream);
        }
        
        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 35px;
            max-width: 1300px;
            margin: 50px auto 0;
        }
        
        .testimonial-card {
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        
        .testimonial-card:hover {
            transform: translateY(-10px);
        }
        
        .testimonial-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 4px solid var(--primary-green);
        }
        
        .testimonial-card p {
            font-style: italic;
            color: #555;
            margin-bottom: 25px;
            line-height: 1.8;
            font-size: 16px;
        }
        
        .testimonial-card h4 {
            color: var(--primary-green);
            font-size: 18px;
        }
        
        .testimonial-card .position {
            color: var(--accent-brown);
            font-size: 14px;
            margin-top: 5px;
        }
        
        /* Donate Section */
        .donate-section {
            padding: 80px 5%;
            background: linear-gradient(135deg, #ffd70020, #2e7d3220);
        }
        
        .donate-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }
        
        .donate-content h2 {
            color: var(--primary-green);
            font-size: 42px;
            margin-bottom: 20px;
        }
        
        .donate-content p {
            font-size: 18px;
            line-height: 1.8;
            color: #666;
            margin-bottom: 30px;
        }
        
        .donate-buttons {
            display: flex;
            gap: 20px;
        }
        
        .donate-primary-btn {
            background-color: var(--donate-gold);
            color: var(--text-dark);
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 18px;
            box-shadow: 0 10px 25px rgba(255,215,0,0.3);
            transition: all 0.3s;
            display: inline-block;
        }
        
        .donate-primary-btn:hover {
            background-color: #e6c200;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(255,215,0,0.4);
        }
        
        .donate-secondary-btn {
            background-color: transparent;
            color: var(--primary-green);
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 18px;
            border: 2px solid var(--primary-green);
            transition: all 0.3s;
            display: inline-block;
        }
        
        .donate-secondary-btn:hover {
            background-color: var(--primary-green);
            color: white;
            transform: translateY(-3px);
        }
        
        .recent-donations-box {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .recent-donations-box h3 {
            color: var(--primary-green);
            font-size: 24px;
            margin-bottom: 20px;
        }
        
        .donation-item {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .donation-item:last-child {
            border-bottom: none;
        }
        
        .donor-name {
            color: var(--text-dark);
        }
        
        .donation-amount {
            font-weight: 700;
            color: var(--primary-green);
        }
        
        .no-donations {
            color: #999;
            text-align: center;
            padding: 20px;
        }
        
        /* CTA Section */
        .cta-section {
            padding: 80px 5%;
            background: linear-gradient(rgba(46,125,50,0.9), rgba(46,125,50,0.9)), url('https://images.unsplash.com/photo-1552799446-159ba9523315?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            text-align: center;
            color: white;
        }
        
        .cta-section h2 {
            font-size: 42px;
            margin-bottom: 20px;
        }
        
        .cta-section p {
            font-size: 20px;
            max-width: 800px;
            margin: 0 auto 40px;
        }
        
        .cta-section .cta-button {
            background-color: var(--accent-brown);
        }
        
        .cta-section .cta-button:hover {
            background-color: #6b431f;
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
            font-size: 16px;
            transition: color 0.3s;
        }
        
        .footer-section a:hover {
            color: var(--secondary-cream);
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #2a3a40;
            color: #8a9ca0;
            font-size: 14px;
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Protecting Our Planet, Securing Our Future</h1>
            <p>Join thousands of volunteers worldwide in creating a sustainable and greener tomorrow</p>
            <a href="register.php" class="cta-button">Become a Member</a>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission">
        <h2 class="section-title">Our Mission</h2>
        <div class="mission-content">
            <div class="mission-text">
                <h3>Healing the Earth, One Community at a Time</h3>
                <p>Founded in 2010, Green Earth NGO is a non-governmental organization dedicated to environmental conservation, sustainable development, and community empowerment. We believe that protecting our planet requires collective action and unwavering commitment.</p>
                <p>Through our various programs, we've successfully planted over 50,000 trees, cleaned hundreds of beaches, and educated thousands of children about environmental stewardship. Our approach combines on-ground action with policy advocacy to create lasting change.</p>
                <p>We work with local communities, governments, and international partners to address the most pressing environmental challenges of our time.</p>
            </div>
            <div class="mission-image">
                <img src="https://images.unsplash.com/photo-1552799446-159ba9523315?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="Volunteers planting trees">
            </div>
        </div>
    </section>

    <!-- NEW SECTION 1: TOP HELPS -->
    <section class="top-helps">
        <h2 class="section-title">Top Help Initiatives</h2>
        <div class="helps-grid">
            <div class="help-card">
                <div class="help-icon">🌱</div>
                <h3>Tree Planting</h3>
                <p>Reforesting degraded lands and creating green belts in urban areas</p>
                <div class="help-stats">50,000+ <span>trees</span></div>
            </div>
            <div class="help-card">
                <div class="help-icon">🌊</div>
                <h3>Ocean Cleanup</h3>
                <p>Removing plastic waste from beaches and oceans worldwide</p>
                <div class="help-stats">100+ <span>cleanups</span></div>
            </div>
            <div class="help-card">
                <div class="help-icon">📚</div>
                <h3>Education</h3>
                <p>Environmental education programs in schools and communities</p>
                <div class="help-stats">15,000+ <span>students</span></div>
            </div>
            <div class="help-card">
                <div class="help-icon">🏥</div>
                <h3>Healthcare Camps</h3>
                <p>Free medical camps in remote and underserved areas</p>
                <div class="help-stats">25,000+ <span>patients</span></div>
            </div>
            <div class="help-card">
                <div class="help-icon">👩‍🌾</div>
                <h3>Women Empowerment</h3>
                <p>Skill development and micro-finance for rural women</p>
                <div class="help-stats">3,000+ <span>women</span></div>
            </div>
            <div class="help-card">
                <div class="help-icon">🐾</div>
                <h3>Wildlife Protection</h3>
                <p>Protecting endangered species and their habitats</p>
                <div class="help-stats">50+ <span>species</span></div>
            </div>
        </div>
    </section>

    <!-- Impact Numbers -->
    <section class="impact">
        <div class="impact-item">
            <h2>50,000+</h2>
            <p>Trees Planted</p>
        </div>
        <div class="impact-item">
            <h2>120+</h2>
            <p>Communities Reached</p>
        </div>
        <div class="impact-item">
            <h2>350+</h2>
            <p>Clean-up Drives</p>
        </div>
        <div class="impact-item">
            <h2>8,500+</h2>
            <p>Active Volunteers</p>
        </div>
        <div class="impact-item">
            <h2>25+</h2>
            <p>Partner Countries</p>
        </div>
    </section>

    <!-- NEW SECTION 2: ACHIEVEMENTS -->
    <section class="achievements">
        <h2 class="section-title" style="color: white;">Our Achievements</h2>
        <div class="achievements-grid">
            <div class="achievement-card">
                <div class="achievement-icon">🏆</div>
                <h3>2023</h3>
                <p>UNEP Global Climate Action Award</p>
                <div class="achievement-year">United Nations</div>
            </div>
            <div class="achievement-card">
                <div class="achievement-icon">🌟</div>
                <h3>2022</h3>
                <p>Green World Environment Award</p>
                <div class="achievement-year">World Environment Foundation</div>
            </div>
            <div class="achievement-card">
                <div class="achievement-icon">🌍</div>
                <h3>2021</h3>
                <p>Earth Day Network Leadership Award</p>
                <div class="achievement-year">Earth Day Network</div>
            </div>
            <div class="achievement-card">
                <div class="achievement-icon">💚</div>
                <h3>2020</h3>
                <p>Excellence in Community Service</p>
                <div class="achievement-year">Global NGO Forum</div>
            </div>
            <div class="achievement-card">
                <div class="achievement-icon">📜</div>
                <h3>2019</h3>
                <p>Special Consultative Status</p>
                <div class="achievement-year">UN Economic and Social Council</div>
            </div>
            <div class="achievement-card">
                <div class="achievement-icon">⭐</div>
                <h3>2018</h3>
                <p>President's Volunteer Service Award</p>
                <div class="achievement-year">USA Government</div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section class="programs">
        <h2 class="section-title">Our Key Programs</h2>
        <div class="program-grid">
            <div class="program-card">
                <img src="https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="Tree planting">
                <h3>Reforestation Initiative</h3>
                <p>We plant native trees in deforested areas to restore ecosystems, combat climate change, and provide livelihood opportunities for local communities.</p>
            </div>
            <div class="program-card">
                <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="Ocean cleanup">
                <h3>Ocean Conservation</h3>
                <p>Our coastal cleanup drives remove tons of plastic waste from beaches and oceans, protecting marine life and preserving biodiversity.</p>
            </div>
            <div class="program-card">
                <img src="https://images.pexels.com/photos/256417/pexels-photo-256417.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Children learning about environment">
                <h3>Eco-Education</h3>
                <p>We conduct workshops in schools and communities to raise awareness about environmental issues and sustainable practices.</p>
            </div>
            <div class="program-card">
                <img src="https://images.unsplash.com/photo-1589656966895-2f33e7653819?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="Wildlife protection">
                <h3>Wildlife Protection</h3>
                <p>Working with forest departments to protect endangered species and their habitats from poaching and habitat destruction.</p>
            </div>
            <div class="program-card">
                <img src="https://images.pexels.com/photos/2886937/pexels-photo-2886937.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Farmer in sustainable agriculture">
                <h3>Sustainable Agriculture</h3>
                <p>Promoting organic farming and sustainable agricultural practices among rural communities to protect soil health.</p>
            </div>
            <div class="program-card">
                <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="Renewable energy">
                <h3>Renewable Energy</h3>
                <p>Installing solar panels and promoting clean energy solutions in off-grid communities across developing nations.</p>
            </div>
        </div>
    </section>

    <!-- NEW SECTION 3: SPONSORS -->
    <section class="sponsors">
        <h2 class="section-title">Our Valued Sponsors & Partners</h2>
        <div class="sponsors-grid">
            <div class="sponsor-item">
                <div class="sponsor-logo">🏢</div>
                <h4>EcoCorp</h4>
                <p>Global Partner</p>
            </div>
            <div class="sponsor-item">
                <div class="sponsor-logo">🌿</div>
                <h4>Green Foundation</h4>
                <p>Funding Partner</p>
            </div>
            <div class="sponsor-item">
                <div class="sponsor-logo">⚡</div>
                <h4>Siemens Energy</h4>
                <p>Technology Partner</p>
            </div>
            <div class="sponsor-item">
                <div class="sponsor-logo">📦</div>
                <h4>Tetra Pak</h4>
                <p>Sustainability Partner</p>
            </div>
            <div class="sponsor-item">
                <div class="sponsor-logo">🏛️</div>
                <h4>UN Environment</h4>
                <p>Strategic Partner</p>
            </div>
            <div class="sponsor-item">
                <div class="sponsor-logo">🎓</div>
                <h4>Stanford University</h4>
                <p>Research Partner</p>
            </div>
            <div class="sponsor-item">
                <div class="sponsor-logo">📸</div>
                <h4>National Geographic</h4>
                <p>Media Partner</p>
            </div>
            <div class="sponsor-item">
                <div class="sponsor-logo">💚</div>
                <h4>WWF</h4>
                <p>Conservation Partner</p>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials">
        <h2 class="section-title">Voices of Change</h2>
        <div class="testimonial-grid">
            <div class="testimonial-card">
                <img src="https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Sarah Johnson">
                <p>"Volunteering with Green Earth has transformed my perspective on environmental conservation. The team's dedication is truly inspiring, and I've seen real impact in our local community."</p>
                <h4>Sarah Johnson</h4>
                <div class="position">Volunteer, 4 years</div>
            </div>
            <div class="testimonial-card">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Michael Chen">
                <p>"The reforestation program in our region has brought back native bird species and improved air quality. I'm proud to be part of such a meaningful initiative."</p>
                <h4>Michael Chen</h4>
                <div class="position">Project Coordinator</div>
            </div>
            <div class="testimonial-card">
                <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Priya Patel">
                <p>"The education programs have made my children more environmentally conscious. They now teach us about recycling and conservation at home!"</p>
                <h4>Priya Patel</h4>
                <div class="position">Parent & Supporter</div>
            </div>
        </div>
    </section>

    <!-- DONATE SECTION -->
    <?php
    // Get recent donations from database
    $recent_query = "SELECT donor_name, amount, is_anonymous FROM donations ORDER BY donation_date DESC LIMIT 5";
    $recent_result = mysqli_query($conn, $recent_query);
    ?>
    <section class="donate-section">
        <div class="donate-container">
            <div class="donate-content">
                <h2>Support Our Cause</h2>
                <p>Your donations help us plant trees, clean oceans, educate communities, and protect wildlife. Every contribution, big or small, makes a real difference in creating a sustainable future.</p>
                <div class="donate-buttons">
                    <a href="donate.php" class="donate-primary-btn">💰 Donate Now</a>
                    <a href="view_funds.php" class="donate-secondary-btn">View Funds</a>
                </div>
            </div>
            <div class="recent-donations-box">
                <h3>Recent Donations</h3>
                <?php if($recent_result && mysqli_num_rows($recent_result) > 0): ?>
                    <?php while($donation = mysqli_fetch_assoc($recent_result)): ?>
                    <div class="donation-item">
                        <span class="donor-name"><?php echo $donation['is_anonymous'] ? 'Anonymous' : htmlspecialchars($donation['donor_name']); ?></span>
                        <span class="donation-amount">$<?php echo number_format($donation['amount'], 2); ?></span>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                <div class="no-donations">No donations yet. Be the first!</div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2>Ready to Make a Difference?</h2>
        <p>Join our global community of changemakers working towards a sustainable future</p>
        <a href="register.php" class="cta-button">Join Us Today</a>
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
                Address: 123 Green Avenue, Earth City, EC 12345<br>
                Hours: Mon-Fri 9am-6pm</p>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <p>Facebook | Twitter | Instagram<br>
                LinkedIn | YouTube</p>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2024 Green Earth NGO - Non-governmental organization. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>