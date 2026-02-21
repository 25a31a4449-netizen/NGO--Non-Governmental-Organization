<?php require_once 'config.php'; ?>
<?php
// Get gallery categories
$categories_query = "SELECT DISTINCT category FROM gallery WHERE category IS NOT NULL";
$categories_result = mysqli_query($conn, $categories_query);

// Get category filter
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

// Get gallery items with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

$category_filter = $category ? "WHERE category = '$category'" : "";
$count_query = "SELECT COUNT(*) as total FROM gallery $category_filter";
$count_result = mysqli_query($conn, $count_query);
$total_images = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_images / $limit);

$query = "SELECT * FROM gallery $category_filter ORDER BY uploaded_at DESC LIMIT $offset, $limit";
$result = mysqli_query($conn, $query);

// Get featured images (most viewed)
$featured_query = "SELECT * FROM gallery ORDER BY views DESC, uploaded_at DESC LIMIT 6";
$featured_result = mysqli_query($conn, $featured_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Earth NGO | Gallery</title>
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
            --gallery-blue: #3498db;
            --gallery-purple: #9b59b6;
            --gallery-teal: #1abc9c;
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
            background: linear-gradient(rgba(46,125,50,0.9), rgba(46,125,50,0.9)), 
            url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
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
        
        /* Gallery Stats */
        .gallery-stats {
            background: var(--primary-green);
            color: white;
            padding: 30px 5%;
            text-align: center;
        }
        
        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }
        
        .stat-item {
            text-align: center;
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
        
        /* Featured Gallery */
        .featured-gallery {
            padding: 60px 5%;
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
        }
        
        .section-title {
            text-align: center;
            font-size: 36px;
            color: var(--primary-green);
            margin-bottom: 40px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-green), var(--donate-gold));
        }
        
        .featured-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 1300px;
            margin: 0 auto;
        }
        
        .featured-item {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            height: 250px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            cursor: pointer;
        }
        
        .featured-item.large {
            grid-column: span 2;
            height: 350px;
        }
        
        .featured-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .featured-item:hover img {
            transform: scale(1.1);
        }
        
        .featured-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            color: white;
            padding: 30px 20px 20px;
            transform: translateY(100%);
            transition: transform 0.3s;
        }
        
        .featured-item:hover .featured-overlay {
            transform: translateY(0);
        }
        
        .featured-overlay h3 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .featured-overlay p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        /* Gallery Filter */
        .gallery-filter {
            padding: 40px 5%;
            background-color: white;
            border-bottom: 1px solid #eee;
        }
        
        .filter-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 12px 25px;
            border: 2px solid var(--primary-green);
            background: transparent;
            color: var(--primary-green);
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .filter-btn:hover,
        .filter-btn.active {
            background-color: var(--primary-green);
            color: white;
        }
        
        /* Gallery Grid */
        .gallery-section {
            padding: 60px 5%;
            background-color: var(--light-gray);
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .gallery-item {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s;
            height: 280px;
        }
        
        .gallery-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
            display: block;
        }
        
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        
        .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.9));
            color: white;
            padding: 30px 20px 20px;
            transform: translateY(100%);
            transition: transform 0.3s;
        }
        
        .gallery-item:hover .gallery-overlay {
            transform: translateY(0);
        }
        
        .gallery-overlay h3 {
            font-size: 20px;
            margin-bottom: 8px;
        }
        
        .gallery-overlay p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 10px;
        }
        
        .gallery-meta {
            display: flex;
            gap: 15px;
            font-size: 12px;
            color: rgba(255,255,255,0.8);
        }
        
        .gallery-category {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--primary-green);
            color: white;
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            z-index: 2;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        /* Lightbox Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.95);
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            max-width: 90%;
            max-height: 90vh;
            position: relative;
            animation: zoomIn 0.3s ease;
        }
        
        @keyframes zoomIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .modal-content img {
            width: 100%;
            height: auto;
            max-height: 80vh;
            object-fit: contain;
            border-radius: 10px;
        }
        
        .modal-info {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-top: 20px;
            max-width: 600px;
        }
        
        .modal-info h2 {
            color: var(--primary-green);
            margin-bottom: 10px;
        }
        
        .modal-info p {
            color: #666;
            line-height: 1.6;
        }
        
        .close-modal {
            position: absolute;
            top: -40px;
            right: 0;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .close-modal:hover {
            color: var(--donate-gold);
        }
        
        .modal-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 50px;
            cursor: pointer;
            padding: 20px;
            user-select: none;
            transition: color 0.3s;
            background: rgba(0,0,0,0.3);
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-nav:hover {
            color: var(--donate-gold);
            background: rgba(0,0,0,0.5);
        }
        
        .modal-nav.prev {
            left: 20px;
        }
        
        .modal-nav.next {
            right: 20px;
        }
        
        /* Upload Section */
        .upload-section {
            padding: 40px 5%;
            background-color: white;
            text-align: center;
        }
        
        .upload-btn {
            background-color: var(--primary-green);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(46,125,50,0.3);
        }
        
        .upload-btn:hover {
            background-color: #1b5e20;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(46,125,50,0.4);
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 50px;
        }
        
        .pagination a, .pagination span {
            padding: 12px 20px;
            background: white;
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .pagination a:hover {
            background: var(--primary-green);
            color: white;
        }
        
        .pagination .active {
            background: var(--primary-green);
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
        
        /* Responsive */
        @media (max-width: 992px) {
            .featured-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .featured-item.large {
                grid-column: span 2;
            }
            
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
            
            .featured-grid {
                grid-template-columns: 1fr;
            }
            
            .featured-item.large {
                grid-column: span 1;
            }
            
            .modal-nav {
                width: 50px;
                height: 50px;
                font-size: 30px;
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
            <h1>Photo Gallery</h1>
            <p>Capturing moments of impact, beauty, and change from around the world</p>
        </div>
    </section>

    <!-- Gallery Stats -->
    <section class="gallery-stats">
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-number">500+</div>
                <div class="stat-label">Photos</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">25+</div>
                <div class="stat-label">Countries</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">50k+</div>
                <div class="stat-label">Views</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">100+</div>
                <div class="stat-label">Contributors</div>
            </div>
        </div>
    </section>

    <!-- Featured Gallery -->
    <section class="featured-gallery">
        <h2 class="section-title">Featured Moments</h2>
        <div class="featured-grid">
            <!-- Featured Image 1 - Large -->
            <div class="featured-item large" onclick="openModal('https://images.unsplash.com/photo-1552799446-159ba9523315?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80', 'Tree Planting Festival', 'Volunteers planting trees in Oregon - Spring 2024')">
                <img src="https://images.unsplash.com/photo-1552799446-159ba9523315?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="Tree planting">
                <div class="featured-overlay">
                    <h3>🌳 Tree Planting Festival</h3>
                    <p>500+ volunteers • 2,000 trees planted</p>
                </div>
            </div>
            
            <!-- Featured Image 2 -->
            <div class="featured-item" onclick="openModal('https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80', 'Beach Cleanup', 'Miami Beach cleanup drive - June 2024')">
                <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="Beach cleanup">
                <div class="featured-overlay">
                    <h3>🌊 Beach Cleanup</h3>
                    <p>2 tons of plastic removed</p>
                </div>
            </div>
            
            <!-- Featured Image 3 -->
            <div class="featured-item" onclick="openModal('https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80', 'Urban Forest', 'Downtown Chicago tree planting project')">
                <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80" alt="Urban forest">
                <div class="featured-overlay">
                    <h3>🏙️ Urban Forest</h3>
                    <p>Greening our cities</p>
                </div>
            </div>
            
            <!-- Featured Image 4 -->
            <div class="featured-item" onclick="openModal('https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80', 'Wildlife Protection', 'Protecting endangered species in Kenya')">
                <img src="https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80" alt="Wildlife">
                <div class="featured-overlay">
                    <h3>🦁 Wildlife Protection</h3>
                    <p>Conservation in action</p>
                </div>
            </div>
            
            <!-- Featured Image 5 -->
            <div class="featured-item" onclick="openModal('https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Elephant Conservation', 'Working with local communities to protect elephants')">
                <img src="https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Elephant">
                <div class="featured-overlay">
                    <h3>🐘 Elephant Conservation</h3>
                    <p>Protecting Africa's giants</p>
                </div>
            </div>
            
            <!-- Featured Image 6 -->
            <div class="featured-item" onclick="openModal('https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-4.0.3&auto=format&fit=crop&w=2072&q=80', 'Solar Project', 'Installing solar panels in rural communities')">
                <img src="https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-4.0.3&auto=format&fit=crop&w=2072&q=80" alt="Solar panels">
                <div class="featured-overlay">
                    <h3>☀️ Solar Project</h3>
                    <p>Clean energy for all</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Filter -->
    <section class="gallery-filter">
        <div class="filter-container">
            <a href="gallery.php" class="filter-btn <?php echo !$category ? 'active' : ''; ?>">All Photos</a>
            <a href="?category=Tree Planting" class="filter-btn <?php echo $category == 'Tree Planting' ? 'active' : ''; ?>">🌳 Tree Planting</a>
            <a href="?category=Beach Cleanup" class="filter-btn <?php echo $category == 'Beach Cleanup' ? 'active' : ''; ?>">🌊 Beach Cleanup</a>
            <a href="?category=Wildlife" class="filter-btn <?php echo $category == 'Wildlife' ? 'active' : ''; ?>">🦁 Wildlife</a>
            <a href="?category=Community" class="filter-btn <?php echo $category == 'Community' ? 'active' : ''; ?>">👥 Community</a>
            <a href="?category=Education" class="filter-btn <?php echo $category == 'Education' ? 'active' : ''; ?>">📚 Education</a>
            <a href="?category=Events" class="filter-btn <?php echo $category == 'Events' ? 'active' : ''; ?>">🎉 Events</a>
        </div>
    </section>

    <!-- Upload Section for Logged-in Users -->
    <?php if(isset($_SESSION['user_id'])): ?>
    <section class="upload-section">
        <a href="gallery-upload.php" class="upload-btn">📸 Upload Your Photos</a>
    </section>
    <?php endif; ?>

    <!-- Gallery Grid -->
    <section class="gallery-section">
        <div class="gallery-grid" id="galleryGrid">
            <!-- Tree Planting Category -->
            <div class="gallery-item" data-category="Tree Planting" onclick="openModal('https://images.unsplash.com/photo-1552799446-159ba9523315?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80', 'Spring Tree Planting', 'Volunteers planting native trees in Portland - April 2024', 'Tree Planting', '1.2k views')">
                <img src="https://images.unsplash.com/photo-1552799446-159ba9523315?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="Tree planting">
                <span class="gallery-category">🌳 Tree Planting</span>
                <div class="gallery-overlay">
                    <h3>Spring Tree Planting</h3>
                    <p>Portland, Oregon • April 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 1.2k views</span>
                        <span>📅 2 months ago</span>
                    </div>
                </div>
            </div>
            
            <div class="gallery-item" data-category="Tree Planting" onclick="openModal('https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80', 'Urban Forest Project', 'Planting trees in downtown Chicago to combat heat island effect', 'Tree Planting', '856 views')">
                <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80" alt="Urban trees">
                <span class="gallery-category">🌳 Tree Planting</span>
                <div class="gallery-overlay">
                    <h3>Urban Forest Project</h3>
                    <p>Chicago, Illinois • May 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 856 views</span>
                        <span>📅 1 month ago</span>
                    </div>
                </div>
            </div>
            
            <div class="gallery-item" data-category="Tree Planting" onclick="openModal('https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Community Garden', 'Local residents planting vegetables in community garden', 'Tree Planting', '2.1k views')">
                <img src="https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Community garden">
                <span class="gallery-category">🌳 Tree Planting</span>
                <div class="gallery-overlay">
                    <h3>Community Garden</h3>
                    <p>Austin, Texas • March 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 2.1k views</span>
                        <span>📅 3 months ago</span>
                    </div>
                </div>
            </div>
            
            <!-- Beach Cleanup Category -->
            <div class="gallery-item" data-category="Beach Cleanup" onclick="openModal('https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80', 'Miami Beach Cleanup', 'Volunteers removing plastic waste from Miami Beach', 'Beach Cleanup', '3.4k views')">
                <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="Beach cleanup">
                <span class="gallery-category">🌊 Beach Cleanup</span>
                <div class="gallery-overlay">
                    <h3>Miami Beach Cleanup</h3>
                    <p>Miami, Florida • June 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 3.4k views</span>
                        <span>📅 2 weeks ago</span>
                    </div>
                </div>
            </div>
            
            <div class="gallery-item" data-category="Beach Cleanup" onclick="openModal('https://images.unsplash.com/photo-1621451537084-4822a3c99d3b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1974&q=80', 'Ocean Cleanup', 'Removing plastic from the Pacific Ocean', 'Beach Cleanup', '5.6k views')">
                <img src="https://images.unsplash.com/photo-1621451537084-4822a3c99d3b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1974&q=80" alt="Ocean cleanup">
                <span class="gallery-category">🌊 Beach Cleanup</span>
                <div class="gallery-overlay">
                    <h3>Ocean Cleanup</h3>
                    <p>Pacific Ocean • May 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 5.6k views</span>
                        <span>📅 1 month ago</span>
                    </div>
                </div>
            </div>
            
            <div class="gallery-item" data-category="Beach Cleanup" onclick="openModal('https://images.unsplash.com/photo-1618477462146-050d241d0b79?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'River Cleanup', 'Cleaning the Hudson River banks', 'Beach Cleanup', '987 views')">
                <img src="https://images.unsplash.com/photo-1618477462146-050d241d0b79?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="River cleanup">
                <span class="gallery-category">🌊 Beach Cleanup</span>
                <div class="gallery-overlay">
                    <h3>River Cleanup</h3>
                    <p>Hudson River, NY • April 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 987 views</span>
                        <span>📅 2 months ago</span>
                    </div>
                </div>
            </div>
            
            <!-- Wildlife Category -->
            <div class="gallery-item" data-category="Wildlife" onclick="openModal('https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80', 'Elephant Family', 'A family of elephants in Kenya', 'Wildlife', '8.2k views')">
                <img src="https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80" alt="Elephants">
                <span class="gallery-category">🦁 Wildlife</span>
                <div class="gallery-overlay">
                    <h3>Elephant Family</h3>
                    <p>Amboseli, Kenya • March 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 8.2k views</span>
                        <span>📅 3 months ago</span>
                    </div>
                </div>
            </div>
            
            <div class="gallery-item" data-category="Wildlife" onclick="openModal('https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Lion Conservation', 'Protecting lions in the Maasai Mara', 'Wildlife', '6.7k views')">
                <img src="https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Lion">
                <span class="gallery-category">🦁 Wildlife</span>
                <div class="gallery-overlay">
                    <h3>Lion Conservation</h3>
                    <p>Maasai Mara, Kenya • April 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 6.7k views</span>
                        <span>📅 2 months ago</span>
                    </div>
                </div>
            </div>
            
            <div class="gallery-item" data-category="Wildlife" onclick="openModal('https://images.unsplash.com/photo-1444464666168-49d633b86797?ixlib=rb-4.0.3&auto=format&fit=crop&w=2072&q=80', 'Bird Watching', 'Rare bird species spotted in Costa Rica', 'Wildlife', '3.1k views')">
                <img src="https://images.unsplash.com/photo-1444464666168-49d633b86797?ixlib=rb-4.0.3&auto=format&fit=crop&w=2072&q=80" alt="Bird">
                <span class="gallery-category">🦁 Wildlife</span>
                <div class="gallery-overlay">
                    <h3>Bird Watching</h3>
                    <p>Costa Rica • May 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 3.1k views</span>
                        <span>📅 1 month ago</span>
                    </div>
                </div>
            </div>
            
            <div class="gallery-item" data-category="Wildlife" onclick="openModal('https://images.unsplash.com/photo-1589656966895-2f33e7653819?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80', 'Sea Turtle', 'Protecting sea turtle nests', 'Wildlife', '4.5k views')">
                <img src="https://images.unsplash.com/photo-1589656966895-2f33e7653819?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="Sea turtle">
                <span class="gallery-category">🦁 Wildlife</span>
                <div class="gallery-overlay">
                    <h3>Sea Turtle</h3>
                    <p>Costa Rica • March 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 4.5k views</span>
                        <span>📅 3 months ago</span>
                    </div>
                </div>
            </div>
            
            <!-- Community Category -->
            <div class="gallery-item" data-category="Community" onclick="openModal('https://images.unsplash.com/photo-1593113598335-c288c59f9681?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Community Meeting', 'Local community discussing environmental initiatives', 'Community', '2.3k views')">
                <img src="https://images.unsplash.com/photo-1593113598335-c288c59f9681?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Community meeting">
                <span class="gallery-category">👥 Community</span>
                <div class="gallery-overlay">
                    <h3>Community Meeting</h3>
                    <p>Seattle, WA • April 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 2.3k views</span>
                        <span>📅 2 months ago</span>
                    </div>
                </div>
            </div>
            
            <div class="gallery-item" data-category="Community" onclick="openModal('https://images.unsplash.com/photo-1573164574472-797cdf4a583a?ixlib=rb-4.0.3&auto=format&fit=crop&w=2069&q=80', 'Volunteer Group', 'Diverse group of volunteers after tree planting', 'Community', '3.8k views')">
                <img src="https://images.unsplash.com/photo-1573164574472-797cdf4a583a?ixlib=rb-4.0.3&auto=format&fit=crop&w=2069&q=80" alt="Volunteers">
                <span class="gallery-category">👥 Community</span>
                <div class="gallery-overlay">
                    <h3>Volunteer Group</h3>
                    <p>Portland, OR • May 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 3.8k views</span>
                        <span>📅 1 month ago</span>
                    </div>
                </div>
            </div>
            
            <!-- Education Category -->
            <div class="gallery-item" data-category="Education" onclick="openModal('https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Environmental Education', 'Teaching children about recycling', 'Education', '4.2k views')">
                <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Environmental education">
                <span class="gallery-category">📚 Education</span>
                <div class="gallery-overlay">
                    <h3>Environmental Education</h3>
                    <p>Austin, TX • March 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 4.2k views</span>
                        <span>📅 3 months ago</span>
                    </div>
                </div>
            </div>
            
            <div class="gallery-item" data-category="Education" onclick="openModal('https://images.unsplash.com/photo-1552581234-26160f608093?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80', 'School Workshop', 'Interactive workshop on sustainability', 'Education', '5.1k views')">
                <img src="https://images.unsplash.com/photo-1552581234-26160f608093?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="School workshop">
                <span class="gallery-category">📚 Education</span>
                <div class="gallery-overlay">
                    <h3>School Workshop</h3>
                    <p>Chicago, IL • April 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 5.1k views</span>
                        <span>📅 2 months ago</span>
                    </div>
                </div>
            </div>
            
            <!-- Events Category -->
            <div class="gallery-item" data-category="Events" onclick="openModal('https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80', 'Climate Summit', 'Annual Climate Action Summit 2024', 'Events', '9.3k views')">
                <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="Climate summit">
                <span class="gallery-category">🎉 Events</span>
                <div class="gallery-overlay">
                    <h3>Climate Summit</h3>
                    <p>New York City • June 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 9.3k views</span>
                        <span>📅 2 weeks ago</span>
                    </div>
                </div>
            </div>
            
            <div class="gallery-item" data-category="Events" onclick="openModal('https://images.unsplash.com/photo-1511632765486-a01980e01a18?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Fundraising Gala', 'Annual fundraising gala for conservation', 'Events', '6.8k views')">
                <img src="https://images.unsplash.com/photo-1511632765486-a01980e01a18?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Fundraising gala">
                <span class="gallery-category">🎉 Events</span>
                <div class="gallery-overlay">
                    <h3>Fundraising Gala</h3>
                    <p>San Francisco, CA • May 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 6.8k views</span>
                        <span>📅 1 month ago</span>
                    </div>
                </div>
            </div>
            
            <!-- Renewable Energy Category -->
            <div class="gallery-item" data-category="Renewable Energy" onclick="openModal('https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-4.0.3&auto=format&fit=crop&w=2072&q=80', 'Solar Installation', 'Installing solar panels in rural community', 'Renewable Energy', '7.2k views')">
                <img src="https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-4.0.3&auto=format&fit=crop&w=2072&q=80" alt="Solar panels">
                <span class="gallery-category">☀️ Renewable Energy</span>
                <div class="gallery-overlay">
                    <h3>Solar Installation</h3>
                    <p>Rural India • March 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 7.2k views</span>
                        <span>📅 3 months ago</span>
                    </div>
                </div>
            </div>
            
            <div class="gallery-item" data-category="Renewable Energy" onclick="openModal('https://images.unsplash.com/photo-1466611653911-95081537e5b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Wind Farm', 'Wind turbines generating clean energy', 'Renewable Energy', '5.9k views')">
                <img src="https://images.unsplash.com/photo-1466611653911-95081537e5b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Wind farm">
                <span class="gallery-category">☀️ Renewable Energy</span>
                <div class="gallery-overlay">
                    <h3>Wind Farm</h3>
                    <p>Texas • April 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 5.9k views</span>
                        <span>📅 2 months ago</span>
                    </div>
                </div>
            </div>
            
            <!-- Recycling Category -->
            <div class="gallery-item" data-category="Recycling" onclick="openModal('https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80', 'Recycling Center', 'Sorting recyclable materials', 'Recycling', '3.7k views')">
                <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="Recycling">
                <span class="gallery-category">♻️ Recycling</span>
                <div class="gallery-overlay">
                    <h3>Recycling Center</h3>
                    <p>Seattle, WA • May 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 3.7k views</span>
                        <span>📅 1 month ago</span>
                    </div>
                </div>
            </div>
            
            <!-- Sustainable Agriculture -->
            <div class="gallery-item" data-category="Agriculture" onclick="openModal('https://images.unsplash.com/photo-1500937386664-56d1dfef4b4b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Organic Farming', 'Sustainable farming practices', 'Agriculture', '4.8k views')">
                <img src="https://images.unsplash.com/photo-1500937386664-56d1dfef4b4b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Organic farming">
                <span class="gallery-category">🌾 Agriculture</span>
                <div class="gallery-overlay">
                    <h3>Organic Farming</h3>
                    <p>California • April 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 4.8k views</span>
                        <span>📅 2 months ago</span>
                    </div>
                </div>
            </div>
            
            <!-- Water Conservation -->
            <div class="gallery-item" data-category="Water" onclick="openModal('https://images.unsplash.com/photo-1527482797697-8795b03a6fe1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Clean Water Project', 'Bringing clean water to communities', 'Water', '6.4k views')">
                <img src="https://images.unsplash.com/photo-1527482797697-8795b03a6fe1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Clean water">
                <span class="gallery-category">💧 Water</span>
                <div class="gallery-overlay">
                    <h3>Clean Water Project</h3>
                    <p>Africa • March 2024</p>
                    <div class="gallery-meta">
                        <span>👁️ 6.4k views</span>
                        <span>📅 3 months ago</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
        <div class="pagination">
            <?php if($page > 1): ?>
                <a href="?page=<?php echo $page-1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>">← Previous</a>
            <?php endif; ?>
            
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <?php if($i == $page): ?>
                    <span class="active"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?page=<?php echo $i; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if($page < $total_pages): ?>
                <a href="?page=<?php echo $page+1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>">Next →</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </section>

    <!-- Lightbox Modal -->
    <div id="galleryModal" class="modal">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <div class="modal-nav prev" onclick="changeImage(-1)">←</div>
        <div class="modal-nav next" onclick="changeImage(1)">→</div>
        <div class="modal-content">
            <img id="modalImage" src="" alt="">
            <div id="modalInfo" class="modal-info">
                <h2 id="modalTitle"></h2>
                <p id="modalDescription"></p>
                <p id="modalMeta" style="color: #999; margin-top: 10px;"></p>
            </div>
        </div>
    </div>

    <!-- Sample SQL Data to Insert -->
    <!--
    INSERT INTO gallery (title, description, image_path, category, views, uploaded_at) VALUES
    ('Spring Tree Planting', 'Volunteers planting native trees in Portland - April 2024', 'https://images.unsplash.com/photo-1552799446-159ba9523315?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80', 'Tree Planting', 1200, '2024-04-15 10:30:00'),
    ('Urban Forest Project', 'Planting trees in downtown Chicago to combat heat island effect', 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80', 'Tree Planting', 856, '2024-05-10 14:20:00'),
    ('Community Garden', 'Local residents planting vegetables in community garden', 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Tree Planting', 2100, '2024-03-20 09:15:00'),
    ('Miami Beach Cleanup', 'Volunteers removing plastic waste from Miami Beach', 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80', 'Beach Cleanup', 3400, '2024-06-05 11:45:00'),
    ('Ocean Cleanup', 'Removing plastic from the Pacific Ocean', 'https://images.unsplash.com/photo-1621451537084-4822a3c99d3b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1974&q=80', 'Beach Cleanup', 5600, '2024-05-18 16:30:00'),
    ('River Cleanup', 'Cleaning the Hudson River banks', 'https://images.unsplash.com/photo-1618477462146-050d241d0b79?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Beach Cleanup', 987, '2024-04-22 13:20:00'),
    ('Elephant Family', 'A family of elephants in Kenya', 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80', 'Wildlife', 8200, '2024-03-25 10:00:00'),
    ('Lion Conservation', 'Protecting lions in the Maasai Mara', 'https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Wildlife', 6700, '2024-04-12 15:45:00'),
    ('Bird Watching', 'Rare bird species spotted in Costa Rica', 'https://images.unsplash.com/photo-1444464666168-49d633b86797?ixlib=rb-4.0.3&auto=format&fit=crop&w=2072&q=80', 'Wildlife', 3100, '2024-05-08 09:30:00'),
    ('Sea Turtle', 'Protecting sea turtle nests', 'https://images.unsplash.com/photo-1589656966895-2f33e7653819?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80', 'Wildlife', 4500, '2024-03-30 14:15:00'),
    ('Community Meeting', 'Local community discussing environmental initiatives', 'https://images.unsplash.com/photo-1593113598335-c288c59f9681?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Community', 2300, '2024-04-28 11:00:00'),
    ('Volunteer Group', 'Diverse group of volunteers after tree planting', 'https://images.unsplash.com/photo-1573164574472-797cdf4a583a?ixlib=rb-4.0.3&auto=format&fit=crop&w=2069&q=80', 'Community', 3800, '2024-05-15 10:30:00'),
    ('Environmental Education', 'Teaching children about recycling', 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Education', 4200, '2024-03-18 13:45:00'),
    ('School Workshop', 'Interactive workshop on sustainability', 'https://images.unsplash.com/photo-1552581234-26160f608093?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80', 'Education', 5100, '2024-04-10 16:20:00'),
    ('Climate Summit', 'Annual Climate Action Summit 2024', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80', 'Events', 9300, '2024-06-01 09:00:00'),
    ('Fundraising Gala', 'Annual fundraising gala for conservation', 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Events', 6800, '2024-05-20 20:30:00'),
    ('Solar Installation', 'Installing solar panels in rural community', 'https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-4.0.3&auto=format&fit=crop&w=2072&q=80', 'Renewable Energy', 7200, '2024-03-22 11:30:00'),
    ('Wind Farm', 'Wind turbines generating clean energy', 'https://images.unsplash.com/photo-1466611653911-95081537e5b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Renewable Energy', 5900, '2024-04-25 14:45:00'),
    ('Recycling Center', 'Sorting recyclable materials', 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80', 'Recycling', 3700, '2024-05-12 10:15:00'),
    ('Organic Farming', 'Sustainable farming practices', 'https://images.unsplash.com/photo-1500937386664-56d1dfef4b4b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Agriculture', 4800, '2024-04-05 13:30:00'),
    ('Clean Water Project', 'Bringing clean water to communities', 'https://images.unsplash.com/photo-1527482797697-8795b03a6fe1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 'Water', 6400, '2024-03-15 09:45:00');
    -->

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
        // Gallery data for navigation
        let currentImageIndex = 0;
        let images = [];
        
        <?php
        // Store all images for navigation
        mysqli_data_seek($result, 0);
        while($item = mysqli_fetch_assoc($result)) {
            echo "images.push({src: '" . $item['image_path'] . "', title: '" . addslashes($item['title']) . "', desc: '" . addslashes($item['description']) . "', category: '" . addslashes($item['category']) . "', views: '" . addslashes($item['views']) . "'});\n";
        }
        ?>
        
        function openModal(src, title, desc, category, views) {
            document.getElementById('galleryModal').style.display = 'flex';
            document.getElementById('modalImage').src = src;
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalDescription').textContent = desc;
            document.getElementById('modalMeta').innerHTML = `📁 ${category} • 👁️ ${views} • 📅 ${new Date().toLocaleDateString()}`;
            
            // Find current image index
            currentImageIndex = images.findIndex(img => img.src === src);
        }
        
        function closeModal() {
            document.getElementById('galleryModal').style.display = 'none';
        }
        
        function changeImage(direction) {
            if (images.length === 0) return;
            
            currentImageIndex += direction;
            if (currentImageIndex < 0) currentImageIndex = images.length - 1;
            if (currentImageIndex >= images.length) currentImageIndex = 0;
            
            const img = images[currentImageIndex];
            document.getElementById('modalImage').src = img.src;
            document.getElementById('modalTitle').textContent = img.title;
            document.getElementById('modalDescription').textContent = img.desc;
            document.getElementById('modalMeta').innerHTML = `📁 ${img.category} • 👁️ ${img.views} • 📅 ${new Date().toLocaleDateString()}`;
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
            if (e.key === 'ArrowLeft') {
                changeImage(-1);
            }
            if (e.key === 'ArrowRight') {
                changeImage(1);
            }
        });
        
        // Filter function (client-side)
        function filterGallery(category) {
            const items = document.querySelectorAll('.gallery-item');
            const filterBtns = document.querySelectorAll('.filter-btn');
            
            filterBtns.forEach(btn => {
                btn.classList.remove('active');
            });
            
            event.target.classList.add('active');
            
            items.forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>