<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Earth NGO | Collaborations</title>
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
            --collab-blue: #3498db;
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
            background: linear-gradient(rgba(52,152,219,0.9), rgba(46,125,50,0.9)), url('https://images.unsplash.com/photo-1559027615-cd4628902d4a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
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
        
        /* Introduction */
        .intro-section {
            padding: 80px 5%;
            text-align: center;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .intro-section h2 {
            color: var(--primary-green);
            font-size: 36px;
            margin-bottom: 25px;
        }
        
        .intro-section p {
            font-size: 18px;
            line-height: 1.8;
            color: #555;
        }
        
        /* Stats Section */
        .collab-stats {
            display: flex;
            justify-content: space-around;
            padding: 60px 5%;
            background: linear-gradient(135deg, var(--primary-green), var(--collab-blue));
            color: white;
            flex-wrap: wrap;
            gap: 40px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-item h3 {
            font-size: 48px;
            margin-bottom: 10px;
        }
        
        .stat-item p {
            font-size: 18px;
            opacity: 0.95;
        }
        
        /* Partners Grid */
        .partners-section {
            padding: 80px 5%;
            background-color: white;
        }
        
        .section-title {
            text-align: center;
            font-size: 42px;
            color: var(--primary-green);
            margin-bottom: 20px;
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
            background: linear-gradient(to right, var(--primary-green), var(--collab-blue));
            border-radius: 2px;
        }
        
        .partners-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            max-width: 1300px;
            margin: 50px auto 0;
        }
        
        .partner-card {
            background: white;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s;
            border-bottom: 5px solid transparent;
        }
        
        .partner-card:hover {
            transform: translateY(-10px);
            border-bottom-color: var(--primary-green);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }
        
        .partner-logo {
            width: 120px;
            height: 120px;
            background: var(--secondary-cream);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 48px;
            color: var(--primary-green);
            border: 3px solid var(--primary-green);
        }
        
        .partner-card h3 {
            color: var(--primary-green);
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .partner-type {
            color: var(--collab-blue);
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .partner-card p {
            color: #666;
            line-height: 1.7;
            margin-bottom: 20px;
        }
        
        .partner-link {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: color 0.3s;
        }
        
        .partner-link:hover {
            color: var(--collab-blue);
        }
        
        /* Collaboration Types */
        .types-section {
            padding: 80px 5%;
            background-color: var(--light-gray);
        }
        
        .types-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 50px auto 0;
        }
        
        .type-card {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }
        
        .type-icon {
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
        }
        
        .type-card h3 {
            color: var(--primary-green);
            margin-bottom: 15px;
        }
        
        .type-card p {
            color: #666;
            line-height: 1.7;
        }
        
        /* Become Partner CTA */
        .partner-cta {
            padding: 80px 5%;
            background: linear-gradient(rgba(46,125,50,0.95), rgba(52,152,219,0.95)), url('https://images.unsplash.com/photo-1559027615-cd4628902d4a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            text-align: center;
            color: white;
        }
        
        .partner-cta h2 {
            font-size: 42px;
            margin-bottom: 20px;
        }
        
        .partner-cta p {
            font-size: 20px;
            max-width: 800px;
            margin: 0 auto 40px;
        }
        
        .cta-button {
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
        
        .cta-button:hover {
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
            <h1>Our Collaborations</h1>
            <p>Working together with global partners to create lasting environmental impact</p>
        </div>
    </section>

    <!-- Introduction -->
    <section class="intro-section">
        <h2>Partners for a Greener Future</h2>
        <p>At Green Earth NGO, we believe that collaboration is key to addressing the world's most pressing environmental challenges. We partner with corporations, foundations, governments, and other non-profits to amplify our impact and create sustainable solutions.</p>
    </section>

    <!-- Stats -->
    <section class="collab-stats">
        <div class="stat-item">
            <h3>120+</h3>
            <p>Active Partners</p>
        </div>
        <div class="stat-item">
            <h3>45</h3>
            <p>Corporate Partners</p>
        </div>
        <div class="stat-item">
            <h3>35</h3>
            <p>NGO Partners</p>
        </div>
        <div class="stat-item">
            <h3>25</h3>
            <p>Government Agencies</p>
        </div>
        <div class="stat-item">
            <h3>15</h3>
            <p>Academic Institutions</p>
        </div>
    </section>

    <!-- Partners Grid -->
    <section class="partners-section">
        <h2 class="section-title">Our Valued Partners</h2>
        <div class="partners-grid">
            <!-- Partner 1 -->
            <div class="partner-card">
                <div class="partner-logo">🏢</div>
                <h3>EcoCorp International</h3>
                <div class="partner-type">Corporate Partner</div>
                <p>Providing sustainable packaging solutions and funding for ocean cleanup initiatives since 2015.</p>
                <a href="#" class="partner-link">Learn More →</a>
            </div>
            
            <!-- Partner 2 -->
            <div class="partner-card">
                <div class="partner-logo">🌿</div>
                <h3>Global Forest Watch</h3>
                <div class="partner-type">NGO Partner</div>
                <p>Collaborating on reforestation projects and forest monitoring in critical biodiversity hotspots.</p>
                <a href="#" class="partner-link">Learn More →</a>
            </div>
            
            <!-- Partner 3 -->
            <div class="partner-card">
                <div class="partner-logo">🏛️</div>
                <h3>UN Environment Programme</h3>
                <div class="partner-type">UN Agency</div>
                <p>Working together on policy advocacy and implementation of sustainable development goals.</p>
                <a href="#" class="partner-link">Learn More →</a>
            </div>
            
            <!-- Partner 4 -->
            <div class="partner-card">
                <div class="partner-logo">🎓</div>
                <h3>Stanford University</h3>
                <div class="partner-type">Academic Partner</div>
                <p>Joint research on climate change impacts and biodiversity conservation strategies.</p>
                <a href="#" class="partner-link">Learn More →</a>
            </div>
            
            <!-- Partner 5 -->
            <div class="partner-card">
                <div class="partner-logo">💚</div>
                <h3>Green Foundation</h3>
                <div class="partner-type">Foundation</div>
                <p>Grant funding for community-based environmental education programs across developing nations.</p>
                <a href="#" class="partner-link">Learn More →</a>
            </div>
            
            <!-- Partner 6 -->
            <div class="partner-card">
                <div class="partner-logo">🌊</div>
                <h3>Ocean Conservancy</h3>
                <div class="partner-type">NGO Partner</div>
                <p>Collaborative efforts to reduce plastic pollution and protect marine ecosystems worldwide.</p>
                <a href="#" class="partner-link">Learn More →</a>
            </div>
            
            <!-- Partner 7 -->
            <div class="partner-card">
                <div class="partner-logo">⚡</div>
                <h3>Siemens Energy</h3>
                <div class="partner-type">Corporate Partner</div>
                <p>Providing renewable energy solutions for off-grid communities in partnership with our branches.</p>
                <a href="#" class="partner-link">Learn More →</a>
            </div>
            
            <!-- Partner 8 -->
            <div class="partner-card">
                <div class="partner-logo">📚</div>
                <h3>National Geographic</h3>
                <div class="partner-type">Media Partner</div>
                <p>Documenting our conservation work and raising awareness through storytelling and photography.</p>
                <a href="#" class="partner-link">Learn More →</a>
            </div>
            
            <!-- Partner 9 -->
            <div class="partner-card">
                <div class="partner-logo">🌱</div>
                <h3>WWF International</h3>
                <div class="partner-type">NGO Partner</div>
                <p>Joint wildlife protection programs and advocacy for endangered species conservation.</p>
                <a href="#" class="partner-link">Learn More →</a>
            </div>
            
            <!-- Partner 10 -->
            <div class="partner-card">
                <div class="partner-logo">🏭</div>
                <h3>Tetra Pak</h3>
                <div class="partner-type">Corporate Partner</div>
                <p>Supporting recycling initiatives and sustainable packaging awareness campaigns.</p>
                <a href="#" class="partner-link">Learn More →</a>
            </div>
            
            <!-- Partner 11 -->
            <div class="partner-card">
                <div class="partner-logo">🏞️</div>
                <h3>National Park Service</h3>
                <div class="partner-type">Government Partner</div>
                <p>Volunteer programs and conservation projects in national parks across North America.</p>
                <a href="#" class="partner-link">Learn More →</a>
            </div>
            
            <!-- Partner 12 -->
            <div class="partner-card">
                <div class="partner-logo">🔬</div>
                <h3>MIT Climate Lab</h3>
                <div class="partner-type">Academic Partner</div>
                <p>Research collaboration on climate change mitigation technologies and solutions.</p>
                <a href="#" class="partner-link">Learn More →</a>
            </div>
        </div>
    </section>

    <!-- Collaboration Types -->
    <section class="types-section">
        <h2 class="section-title">Ways to Collaborate</h2>
        <div class="types-grid">
            <div class="type-card">
                <div class="type-icon">💰</div>
                <h3>Financial Support</h3>
                <p>Provide funding for specific projects, programs, or general operational support through grants or donations.</p>
            </div>
            
            <div class="type-card">
                <div class="type-icon">🤝</div>
                <h3>In-Kind Support</h3>
                <p>Donate products, services, or expertise that help us implement our programs more effectively.</p>
            </div>
            
            <div class="type-card">
                <div class="type-icon">📢</div>
                <h3>Awareness Partners</h3>
                <p>Help amplify our message and reach new audiences through your platforms and networks.</p>
            </div>
            
            <div class="type-card">
                <div class="type-icon">🔬</div>
                <h3>Research Collaboration</h3>
                <p>Partner with us on scientific research, data collection, and environmental studies.</p>
            </div>
            
            <div class="type-card">
                <div class="type-icon">🌍</div>
                <h3>Project Implementation</h3>
                <p>Work together on the ground to implement conservation projects in communities worldwide.</p>
            </div>
            
            <div class="type-card">
                <div class="type-icon">📚</div>
                <h3>Educational Partnerships</h3>
                <p>Develop and deliver environmental education programs for schools and communities.</p>
            </div>
        </div>
    </section>

    <!-- Become Partner CTA -->
    <section class="partner-cta">
        <h2>Become a Partner</h2>
        <p>Join us in creating a sustainable future. We're always looking for new partners who share our vision and commitment to environmental conservation.</p>
        <a href="contact.php" class="cta-button">Partner With Us</a>
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