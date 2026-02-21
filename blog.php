<?php require_once 'config.php'; ?>
<?php
// Get blog posts with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Get category filter
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

// Get search query
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Build query conditions
$conditions = [];
if ($category) {
    $conditions[] = "category = '$category'";
}
if ($search) {
    $conditions[] = "(title LIKE '%$search%' OR content LIKE '%$search%' OR excerpt LIKE '%$search%')";
}
$where_clause = !empty($conditions) ? "WHERE status = 'published' AND " . implode(' AND ', $conditions) : "WHERE status = 'published'";

// Get total posts for pagination
$count_query = "SELECT COUNT(*) as total FROM blog_posts $where_clause";
$count_result = mysqli_query($conn, $count_query);
$total_posts = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_posts / $limit);

// Get posts for current page
$query = "SELECT * FROM blog_posts $where_clause ORDER BY created_at DESC LIMIT $offset, $limit";
$result = mysqli_query($conn, $query);

// Get featured post (most viewed in last 30 days)
$featured_query = "SELECT * FROM blog_posts WHERE status = 'published' ORDER BY views DESC, created_at DESC LIMIT 1";
$featured_result = mysqli_query($conn, $featured_query);
$featured_post = mysqli_fetch_assoc($featured_result);

// Get categories with post counts
$categories_query = "SELECT category, COUNT(*) as count FROM blog_posts WHERE category IS NOT NULL AND status = 'published' GROUP BY category ORDER BY count DESC";
$categories_result = mysqli_query($conn, $categories_query);

// Get recent posts for sidebar
$recent_query = "SELECT id, title, created_at, views FROM blog_posts WHERE status = 'published' ORDER BY created_at DESC LIMIT 5";
$recent_result = mysqli_query($conn, $recent_query);

// Get popular posts by views
$popular_query = "SELECT id, title, views FROM blog_posts WHERE status = 'published' ORDER BY views DESC LIMIT 5";
$popular_result = mysqli_query($conn, $popular_query);

// Get archive months
$archive_query = "SELECT DISTINCT DATE_FORMAT(created_at, '%Y-%m') as month, DATE_FORMAT(created_at, '%M %Y') as month_name, COUNT(*) as count 
                  FROM blog_posts WHERE status = 'published' GROUP BY month ORDER BY month DESC LIMIT 12";
$archive_result = mysqli_query($conn, $archive_query);

// Get total views
$total_views_query = "SELECT SUM(views) as total FROM blog_posts WHERE status = 'published'";
$total_views_result = mysqli_query($conn, $total_views_query);
$total_views = mysqli_fetch_assoc($total_views_result)['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Earth NGO | Blog</title>
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
            --blog-blue: #3498db;
            --blog-purple: #9b59b6;
            --blog-red: #e74c3c;
            --blog-teal: #1abc9c;
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
            background: linear-gradient(rgba(52,152,219,0.9), rgba(46,125,50,0.9)), 
            url('https://images.unsplash.com/photo-1499750310107-5fef28a66643?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            height: 350px;
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
            font-size: 20px;
            max-width: 700px;
            margin: 0 auto;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
        }
        
        .page-stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 30px;
        }
        
        .page-stat {
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 50px;
            backdrop-filter: blur(5px);
        }
        
        /* Blog Layout */
        .blog-wrapper {
            max-width: 1400px;
            margin: 50px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
        }
        
        /* Featured Post */
        .featured-post {
            grid-column: 1 / -1;
            background: linear-gradient(145deg, #f8f9fa, #ffffff);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .featured-image {
            height: 400px;
            overflow: hidden;
        }
        
        .featured-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .featured-image:hover img {
            transform: scale(1.05);
        }
        
        .featured-content {
            padding: 40px 40px 40px 0;
        }
        
        .featured-badge {
            background-color: var(--donate-gold);
            color: var(--text-dark);
            padding: 8px 20px;
            border-radius: 50px;
            display: inline-block;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .featured-title {
            font-size: 32px;
            color: var(--primary-green);
            margin-bottom: 15px;
        }
        
        .featured-meta {
            display: flex;
            gap: 20px;
            color: #666;
            margin-bottom: 20px;
        }
        
        .featured-excerpt {
            color: #555;
            line-height: 1.8;
            margin-bottom: 25px;
            font-size: 16px;
        }
        
        .featured-btn {
            background-color: var(--primary-green);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .featured-btn:hover {
            background-color: #1b5e20;
            transform: translateY(-3px);
        }
        
        /* Search Bar */
        .search-section {
            grid-column: 1 / -1;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .search-form {
            display: flex;
            gap: 15px;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .search-form input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .search-form input:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(46,125,50,0.1);
        }
        
        .search-form button {
            padding: 15px 40px;
            background-color: var(--primary-green);
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .search-form button:hover {
            background-color: #1b5e20;
            transform: translateY(-2px);
        }
        
        /* Blog Posts */
        .blog-posts {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        
        .blog-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .blog-image-wrapper {
            position: relative;
            height: 250px;
            overflow: hidden;
        }
        
        .blog-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .blog-card:hover .blog-image {
            transform: scale(1.1);
        }
        
        .blog-category {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--primary-green);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            z-index: 2;
        }
        
        .blog-content {
            padding: 30px;
        }
        
        .blog-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            color: #666;
            font-size: 14px;
        }
        
        .blog-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .blog-title {
            color: var(--primary-green);
            font-size: 24px;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .blog-title a {
            color: var(--primary-green);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .blog-title a:hover {
            color: var(--blog-blue);
        }
        
        .blog-excerpt {
            color: #555;
            line-height: 1.8;
            margin-bottom: 20px;
        }
        
        .blog-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .read-more {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: gap 0.3s;
        }
        
        .read-more:hover {
            gap: 10px;
            color: var(--blog-blue);
        }
        
        .post-stats {
            display: flex;
            gap: 15px;
            color: #999;
            font-size: 14px;
        }
        
        /* Sidebar */
        .blog-sidebar {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        
        .sidebar-widget {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .widget-title {
            color: var(--primary-green);
            font-size: 22px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--secondary-cream);
            position: relative;
        }
        
        .widget-title::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--primary-green);
        }
        
        /* Categories */
        .category-list {
            list-style: none;
        }
        
        .category-item {
            margin-bottom: 12px;
        }
        
        .category-link {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-dark);
            text-decoration: none;
            padding: 8px 0;
            transition: all 0.3s;
            border-bottom: 1px dashed #eee;
        }
        
        .category-link:hover {
            color: var(--primary-green);
            padding-left: 10px;
        }
        
        .category-count {
            background: var(--light-gray);
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 12px;
            color: #666;
        }
        
        /* Recent Posts */
        .recent-post {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .recent-post:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .recent-post-image {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            object-fit: cover;
        }
        
        .recent-post-info h4 {
            font-size: 16px;
            margin-bottom: 8px;
        }
        
        .recent-post-info a {
            color: var(--text-dark);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .recent-post-info a:hover {
            color: var(--primary-green);
        }
        
        .recent-post-meta {
            display: flex;
            gap: 10px;
            font-size: 12px;
            color: #999;
        }
        
        /* Popular Posts */
        .popular-post {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .popular-post:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .popular-rank {
            width: 30px;
            height: 30px;
            background: var(--donate-gold);
            color: var(--text-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }
        
        .popular-info h4 {
            font-size: 15px;
            margin-bottom: 5px;
        }
        
        .popular-info a {
            color: var(--text-dark);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .popular-info a:hover {
            color: var(--primary-green);
        }
        
        .popular-views {
            font-size: 12px;
            color: #999;
        }
        
        /* Archive */
        .archive-list {
            list-style: none;
        }
        
        .archive-item {
            margin-bottom: 10px;
        }
        
        .archive-link {
            display: flex;
            justify-content: space-between;
            color: var(--text-dark);
            text-decoration: none;
            padding: 5px 0;
            transition: color 0.3s;
        }
        
        .archive-link:hover {
            color: var(--primary-green);
        }
        
        .archive-count {
            color: #999;
            font-size: 14px;
        }
        
        /* Tags */
        .tags-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .tag {
            background: var(--light-gray);
            color: var(--text-dark);
            padding: 8px 15px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .tag:hover {
            background: var(--primary-green);
            color: white;
        }
        
        /* Newsletter Widget */
        .newsletter-widget {
            background: linear-gradient(135deg, var(--primary-green), #1b5e20);
            color: white;
        }
        
        .newsletter-widget .widget-title {
            color: white;
            border-bottom-color: rgba(255,255,255,0.3);
        }
        
        .newsletter-widget .widget-title::after {
            background: var(--donate-gold);
        }
        
        .newsletter-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .newsletter-form input {
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
        }
        
        .newsletter-form button {
            padding: 15px;
            background: var(--donate-gold);
            color: var(--text-dark);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .newsletter-form button:hover {
            background: #e6c200;
            transform: translateY(-2px);
        }
        
        /* Pagination */
        .pagination {
            grid-column: 1 / -1;
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 40px;
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
            .blog-wrapper {
                grid-template-columns: 1fr;
            }
            
            .featured-post {
                grid-template-columns: 1fr;
            }
            
            .featured-content {
                padding: 30px;
            }
        }
        
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 40px;
            }
            
            .search-form {
                flex-direction: column;
            }
            
            .blog-meta {
                flex-wrap: wrap;
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
            <h1>Green Earth Blog</h1>
            <p>Stories, updates, and insights from our environmental journey</p>
            <div class="page-stats">
                <span class="page-stat">📝 <?php echo $total_posts; ?> Articles</span>
                <span class="page-stat">👁️ <?php echo number_format($total_views); ?> Views</span>
                <span class="page-stat">📅 Since 2020</span>
            </div>
        </div>
    </section>

    <div class="blog-wrapper">
        <!-- Search Section -->
        <div class="search-section">
            <form class="search-form" method="GET" action="">
                <input type="text" name="search" placeholder="Search articles..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">🔍 Search</button>
                <?php if($category): ?>
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                <?php endif; ?>
            </form>
        </div>

        <!-- Featured Post -->
        <?php if($featured_post && $page == 1 && !$search): ?>
        <div class="featured-post">
            <div class="featured-image">
                <img src="<?php echo $featured_post['image'] ?: 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80'; ?>" alt="<?php echo $featured_post['title']; ?>">
            </div>
            <div class="featured-content">
                <span class="featured-badge">🌟 Featured Post</span>
                <h2 class="featured-title"><?php echo $featured_post['title']; ?></h2>
                <div class="featured-meta">
                    <span>📅 <?php echo date('F j, Y', strtotime($featured_post['created_at'])); ?></span>
                    <span>👤 <?php echo $featured_post['author'] ?: 'Admin'; ?></span>
                    <span>👁️ <?php echo number_format($featured_post['views']); ?> views</span>
                </div>
                <p class="featured-excerpt"><?php echo $featured_post['excerpt'] ?: substr($featured_post['content'], 0, 300) . '...'; ?></p>
                <a href="blog-post.php?id=<?php echo $featured_post['id']; ?>" class="featured-btn">Read Full Article →</a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Main Blog Posts -->
        <div class="blog-posts">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($post = mysqli_fetch_assoc($result)): ?>
                    <article class="blog-card">
                        <div class="blog-image-wrapper">
                            <img src="<?php echo $post['image'] ?: 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80'; ?>" alt="<?php echo $post['title']; ?>" class="blog-image">
                            <?php if($post['category']): ?>
                                <span class="blog-category"><?php echo $post['category']; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span>📅 <?php echo date('M j, Y', strtotime($post['created_at'])); ?></span>
                                <span>👤 <?php echo $post['author'] ?: 'Admin'; ?></span>
                                <span>⏱️ 5 min read</span>
                            </div>
                            <h3 class="blog-title"><a href="blog-post.php?id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a></h3>
                            <p class="blog-excerpt"><?php echo $post['excerpt'] ?: substr($post['content'], 0, 200) . '...'; ?></p>
                            <div class="blog-footer">
                                <a href="blog-post.php?id=<?php echo $post['id']; ?>" class="read-more">Read More →</a>
                                <div class="post-stats">
                                    <span>👁️ <?php echo number_format($post['views']); ?></span>
                                    <span>💬 12 comments</span>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 60px; background: white; border-radius: 15px;">
                    <h3 style="color: var(--primary-green); margin-bottom: 15px;">No Articles Found</h3>
                    <p style="color: #666;">Try adjusting your search or filter criteria.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="blog-sidebar">
            <!-- Categories Widget -->
            <div class="sidebar-widget">
                <h3 class="widget-title">📂 Categories</h3>
                <ul class="category-list">
                    <?php if(mysqli_num_rows($categories_result) > 0): ?>
                        <?php while($cat = mysqli_fetch_assoc($categories_result)): ?>
                            <li class="category-item">
                                <a href="?category=<?php echo urlencode($cat['category']); ?>" class="category-link">
                                    <span><?php echo $cat['category']; ?></span>
                                    <span class="category-count"><?php echo $cat['count']; ?></span>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li>No categories yet</li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Recent Posts Widget -->
            <div class="sidebar-widget">
                <h3 class="widget-title">🕒 Recent Posts</h3>
                <?php if(mysqli_num_rows($recent_result) > 0): ?>
                    <?php while($recent = mysqli_fetch_assoc($recent_result)): ?>
                        <div class="recent-post">
                            <img src="https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Post thumbnail" class="recent-post-image">
                            <div class="recent-post-info">
                                <h4><a href="blog-post.php?id=<?php echo $recent['id']; ?>"><?php echo $recent['title']; ?></a></h4>
                                <div class="recent-post-meta">
                                    <span>📅 <?php echo date('M j', strtotime($recent['created_at'])); ?></span>
                                    <span>👁️ <?php echo number_format($recent['views']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No recent posts</p>
                <?php endif; ?>
            </div>

            <!-- Popular Posts Widget -->
            <div class="sidebar-widget">
                <h3 class="widget-title">🔥 Popular Posts</h3>
                <?php if(mysqli_num_rows($popular_result) > 0): ?>
                    <?php 
                    $rank = 1;
                    while($popular = mysqli_fetch_assoc($popular_result)): 
                    ?>
                        <div class="popular-post">
                            <div class="popular-rank"><?php echo $rank; ?></div>
                            <div class="popular-info">
                                <h4><a href="blog-post.php?id=<?php echo $popular['id']; ?>"><?php echo $popular['title']; ?></a></h4>
                                <div class="popular-views">👁️ <?php echo number_format($popular['views']); ?> views</div>
                            </div>
                        </div>
                    <?php 
                        $rank++;
                        endwhile; 
                    ?>
                <?php else: ?>
                    <p>No popular posts yet</p>
                <?php endif; ?>
            </div>

            <!-- Archive Widget -->
            <div class="sidebar-widget">
                <h3 class="widget-title">📆 Archives</h3>
                <ul class="archive-list">
                    <?php if(mysqli_num_rows($archive_result) > 0): ?>
                        <?php while($archive = mysqli_fetch_assoc($archive_result)): ?>
                            <li class="archive-item">
                                <a href="?archive=<?php echo $archive['month']; ?>" class="archive-link">
                                    <span><?php echo $archive['month_name']; ?></span>
                                    <span class="archive-count">(<?php echo $archive['count']; ?>)</span>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li>No archives yet</li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Tags Widget -->
            <div class="sidebar-widget">
                <h3 class="widget-title">🏷️ Tags</h3>
                <div class="tags-cloud">
                    <a href="#" class="tag">#ClimateAction</a>
                    <a href="#" class="tag">#Reforestation</a>
                    <a href="#" class="tag">#OceanCleanup</a>
                    <a href="#" class="tag">#Wildlife</a>
                    <a href="#" class="tag">#Sustainability</a>
                    <a href="#" class="tag">#RenewableEnergy</a>
                    <a href="#" class="tag">#ZeroWaste</a>
                    <a href="#" class="tag">#Biodiversity</a>
                    <a href="#" class="tag">#EcoTips</a>
                    <a href="#" class="tag">#GreenLiving</a>
                </div>
            </div>

            <!-- Newsletter Widget -->
            <div class="sidebar-widget newsletter-widget">
                <h3 class="widget-title">✉️ Newsletter</h3>
                <p style="margin-bottom: 15px; opacity: 0.9;">Get the latest articles and updates directly in your inbox.</p>
                <form class="newsletter-form" method="POST" action="subscribe.php">
                    <input type="email" name="email" placeholder="Your email address" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
            <div class="pagination">
                <?php if($page > 1): ?>
                    <a href="?page=<?php echo $page-1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">← Previous</a>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if($i == $page): ?>
                        <span class="active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if($page < $total_pages): ?>
                    <a href="?page=<?php echo $page+1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Next →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sample Blog Posts Data (You can insert these into your database) -->
    <!--
    INSERT INTO blog_posts (title, content, excerpt, author, category, image, views, created_at) VALUES
    ('10 Ways to Reduce Plastic in Your Daily Life', 'Full content here...', 'Discover simple yet effective ways to eliminate single-use plastics from your routine and make a positive impact on the environment.', 'Emma Green', 'Lifestyle', 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 1543, '2024-05-15 10:30:00'),
    ('The Importance of Urban Forests', 'Full content here...', 'How planting trees in cities can combat climate change, reduce pollution, and improve mental health for residents.', 'Dr. Michael Trees', 'Environment', 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80', 2341, '2024-05-10 14:20:00'),
    ('Ocean Cleanup: Success Stories', 'Full content here...', 'Inspiring stories from our recent ocean cleanup missions and the impact on marine life.', 'Sarah Ocean', 'Conservation', 'https://images.unsplash.com/photo-1621451537084-4822a3c99d3b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1974&q=80', 3210, '2024-05-05 09:15:00'),
    ('Beginner''s Guide to Composting', 'Full content here...', 'Everything you need to know to start composting at home, reducing waste and creating nutrient-rich soil.', 'Tom Gardner', 'Gardening', 'https://images.unsplash.com/photo-1516257984-b1b4d707412e?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 1876, '2024-04-28 11:45:00'),
    ('Renewable Energy 101', 'Full content here...', 'Understanding solar, wind, and other renewable energy sources and how you can support the transition.', 'Dr. Sarah Power', 'Energy', 'https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-4.0.3&auto=format&fit=crop&w=2072&q=80', 2654, '2024-04-20 16:30:00'),
    ('Protecting Endangered Species', 'Full content here...', 'Learn about the endangered species in your area and how you can help protect them.', 'James Wildlife', 'Wildlife', 'https://images.unsplash.com/photo-1546182990-dffeafbe841d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 1987, '2024-04-12 13:20:00'),
    ('Sustainable Fashion Guide', 'Full content here...', 'How to build an eco-friendly wardrobe and support ethical fashion brands.', 'Lisa Style', 'Fashion', 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 1432, '2024-04-05 10:00:00'),
    ('Climate Change: Myths vs Facts', 'Full content here...', 'Separating climate change facts from fiction with the latest scientific data.', 'Dr. Robert Climate', 'Science', 'https://images.unsplash.com/photo-1562155955-1cb2d73488d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 3456, '2024-03-28 15:45:00'),
    ('Community Gardening Projects', 'Full content here...', 'How community gardens are bringing people together and providing fresh, local food.', 'Maria Garden', 'Community', 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 1654, '2024-03-20 09:30:00'),
    ('Eco-Friendly Home Improvements', 'Full content here...', 'Simple upgrades that make your home more energy-efficient and environmentally friendly.', 'Mike Home', 'Home', 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 1876, '2024-03-15 14:15:00'),
    ('The Plastic Crisis: What You Need to Know', 'Full content here...', 'Understanding the global plastic pollution crisis and what''s being done about it.', 'Emma Green', 'Environment', 'https://images.unsplash.com/photo-1611284446314-60a58ac0deb0?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 2876, '2024-03-10 11:00:00'),
    ('Bird Watching for Beginners', 'Full content here...', 'How to start bird watching and contribute to citizen science projects.', 'Tom Bird', 'Wildlife', 'https://images.unsplash.com/photo-1444464666168-49d633b86797?ixlib=rb-4.0.3&auto=format&fit=crop&w=2072&q=80', 1234, '2024-03-05 08:45:00'),
    ('Zero Waste Kitchen', 'Full content here...', 'Tips and tricks for reducing waste in your kitchen, from shopping to composting.', 'Lisa Kitchen', 'Lifestyle', 'https://images.unsplash.com/photo-1556911220-bda9f7f7597e?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 1987, '2024-02-28 13:30:00'),
    ('Electric Vehicles: Are They Worth It?', 'Full content here...', 'A comprehensive look at electric vehicles, their costs, benefits, and environmental impact.', 'Mike Auto', 'Transportation', 'https://images.unsplash.com/photo-1593941707882-a5bba14938c7?ixlib=rb-4.0.3&auto=format&fit=crop&w=2072&q=80', 2345, '2024-02-20 16:20:00'),
    ('Sustainable Travel Tips', 'Full content here...', 'How to explore the world while minimizing your environmental footprint.', 'Sarah Travel', 'Travel', 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80', 1654, '2024-02-15 10:15:00');
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
        // Smooth scroll to top
        window.addEventListener('load', function() {
            // Any additional JavaScript can go here
        });
    </script>
</body>
</html>