<?php
require_once 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Update view count
mysqli_query($conn, "UPDATE blog_posts SET views = views + 1 WHERE id = $id");

$query = "SELECT * FROM blog_posts WHERE id = $id AND status = 'published'";
$result = mysqli_query($conn, $query);
$post = mysqli_fetch_assoc($result);

if (!$post) {
    redirect('blog.php');
}

// Get related posts
$related_query = "SELECT * FROM blog_posts WHERE category = '{$post['category']}' AND id != $id AND status = 'published' ORDER BY views DESC LIMIT 3";
$related_result = mysqli_query($conn, $related_query);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $post['title']; ?> | Green Earth NGO</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .blog-post-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 0 20px;
        }
        .blog-post-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        .blog-post-meta {
            color: #666;
            margin-bottom: 20px;
        }
        .blog-post-content {
            line-height: 1.8;
            color: #333;
        }
        .related-posts {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="blog-post-container">
        <img src="<?php echo $post['image'] ?: 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05'; ?>" alt="<?php echo $post['title']; ?>" class="blog-post-image">
        
        <h1><?php echo $post['title']; ?></h1>
        
        <div class="blog-post-meta">
            <span>By <?php echo $post['author'] ?: 'Admin'; ?></span> | 
            <span><?php echo date('F j, Y', strtotime($post['created_at'])); ?></span> | 
            <span>👁️ <?php echo number_format($post['views']); ?> views</span> | 
            <span>📁 <?php echo $post['category']; ?></span>
        </div>
        
        <div class="blog-post-content">
            <?php echo nl2br($post['content']); ?>
        </div>
        
        <?php if(mysqli_num_rows($related_result) > 0): ?>
        <div class="related-posts">
            <h3>Related Posts</h3>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <?php while($related = mysqli_fetch_assoc($related_result)): ?>
                <div>
                    <img src="<?php echo $related['image']; ?>" alt="<?php echo $related['title']; ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 10px;">
                    <h4><a href="blog-post.php?id=<?php echo $related['id']; ?>"><?php echo $related['title']; ?></a></h4>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>