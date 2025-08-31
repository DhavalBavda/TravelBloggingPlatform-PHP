<?php
$isLoggedIn = isset($_SESSION['userid']);

// Pagination parameters
$limit = 3; // blogs per page
$pageNum = isset($_GET['pageNo']) ? max(1, intval($_GET['pageNo'])) : 1;
$offset = ($pageNum - 1) * $limit;

// Search parameter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch blogs from service
$blogsArr = $blogService->getAllBlogs('', $limit, $offset);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog List</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            color: #333;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .logo {
            font-size: 24px;
            font-weight: 600;
            color: #1e90ff;
        }

        header nav a {
            margin-left: 20px;
            padding: 8px 16px;
            border-radius: 10px;
            background-color: #1e90ff;
            color: #fff;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        header nav a:hover {
            background-color: #1c86ee;
        }

        .hero {
            text-align: center;
            padding: 60px 50px;
            background: linear-gradient(135deg, #1e90ff 0%, #87ceeb 100%);
            color: white;
            margin-bottom: 40px;
        }

        .hero h1 {
            font-size: 3rem;
            margin: 0 0 20px 0;
            font-weight: 600;
        }

        .hero p {
            font-size: 1.2rem;
            margin: 0;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto 40px auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #1e90ff;
            font-weight: 600;
            font-size: 32px;
        }

        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }

        .blog-card {
            background-color: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .blog-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .blog-content {
            padding: 25px;
        }

        .blog-content h3 {
            margin: 0 0 15px 0;
            font-size: 1.4rem;
            color: #333;
            font-weight: 600;
        }

        .blog-content p {
            font-size: 1rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
            height: 48px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .blog-meta {
            font-size: 0.9rem;
            color: #999;
            margin-bottom: 15px;
        }

        .read-more {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 10px;
            background-color: #1e90ff;
            color: white;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .read-more:hover {
            background-color: #1c86ee;
        }

        .create-blog-btn {
            display: block;
            width: 200px;
            margin: 0 auto 40px auto;
            padding: 15px;
            background-color: #1e90ff;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            text-align: center;
        }

        .create-blog-btn:hover {
            background-color: #1c86ee;
        }

        .no-blogs {
            text-align: center;
            padding: 60px 20px;
            color: #666;
            font-size: 1.1rem;
        }

        .blog-slider {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
            border-bottom: 1px solid #eee;
        }

        .blog-slider img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 100%;
            opacity: 0;
            transition: left 0.5s ease, opacity 0.5s ease;
        }

        .blog-slider img.active {
            left: 0;
            opacity: 1;
        }

        .search-bar {
            width: 100%;
            max-width: 500px;
            margin: 0 auto 40px auto;
            display: flex;
        }
        .search-bar input[type="text"] {
            flex: 1;
            padding: 12px 15px;
            border-radius: 10px 0 0 10px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        .search-bar button {
            padding: 12px 20px;
            border-radius: 0 10px 10px 0;
            border: none;
            background-color: #1e90ff;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .search-bar button:hover {
            background-color: #1c86ee;
        }

        /* Pagination styling */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        .pagination a {
            padding: 10px 15px;
            margin: 0 5px;
            border-radius: 8px;
            background-color: #1e90ff;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        .pagination a:hover {
            background-color: #1c86ee;
        }
        .pagination .current-page {
            background-color: #333;
        }

    </style>
    <script>
        const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;

        document.addEventListener('DOMContentLoaded', () => {
            const createBtn = document.querySelector('.create-blog-btn');
            createBtn.addEventListener('click', function(e) {
                e.preventDefault();

                if (isLoggedIn) {
                    // Redirect to create blog page if user is logged in
                    window.location.href = "index.php?page=blog&action=create";

                } else {
                    // Redirect to login page if not logged in
                    window.location.href = "index.php?page=auth&action=login";
                }
            });
        });


        document.addEventListener('DOMContentLoaded', () => {
            const sliders = document.querySelectorAll('.blog-slider');
            sliders.forEach(slider => {
                const images = slider.querySelectorAll('img');
                let current = 0;
                if (images.length > 1) {
                    images[current].classList.add('active');
                    setInterval(() => {
                        images[current].classList.remove('active');
                        current = (current + 1) % images.length;
                        images[current].classList.add('active');
                    }, 3000); // change image every 3 seconds
                } else if (images.length === 1) {
                    images[0].classList.add('active'); // single image
                }
            });
        });

    </script>
</head>
<body>

<header>
    <div class="logo">BlogZone</div>
    <nav>
        <a href="#">Home</a>
        <a href="#">Categories</a>
        <a href="#">About</a>
        <a href="#">Contact</a>
        <a href="#">Login</a>
    </nav>
</header>

<div class="hero">
    <h1>Discover Amazing Stories</h1>
    <p>Explore a world of creativity, inspiration, and shared experiences from our community of writers</p>
</div>

<div class="container">
    <a href="#" class="create-blog-btn">✍️ Create New Blog</a>
    
    <!-- Search Bar -->
    <form class="search-bar" method="GET" action="">
        <input type="text" name="search" placeholder="Search blogs..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>

    <div class="blog-grid">
        <?php if(!empty($blogsArr)): ?>
            <?php foreach($blogsArr as $blog): ?>

                <!-- <?php
                    // Handle comma-separated images and get the first one
                    $firstImage = '';
                    if (!empty($blog['IMAGES'])) {
                        $images = explode(',', $blog['IMAGES']);
                        $firstImage = trim($images[0]);
                    }
                    $imgPath = '../media/blog_images/' . $firstImage;
                ?> -->

                <?php
                    // Split images
                    $images = [];
                    if (!empty($blog['IMAGES'])) {
                        $images = array_map('trim', explode(',', $blog['IMAGES']));
                    }
                ?>

                <div class="blog-card">
                    <!-- <img src="<?= htmlspecialchars($imgPath) ?>" alt="Blog Image"> -->
                    <div class="blog-slider">
                        <?php foreach($images as $img): ?>
                            <img src="<?= htmlspecialchars('../media/blog_images/' . $img) ?>" alt="Blog Image">
                        <?php endforeach; ?>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">By <?= htmlspecialchars($blog['AUTHORID']) ?> • <?= htmlspecialchars($blog['CREATEDDATE']) ?></div>
                        <h3><?= htmlspecialchars($blog['TITLE']) ?></h3>
                        <p><?= htmlspecialchars($blog['SHORTDESC']) ?></p>
                        <a href="" class="read-more">Read More</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-blogs">No blogs found.</div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if (!empty($blogsArr)): ?>
        <div class="pagination">

            <!-- Previous Page Link -->
            <?php if ($pageNum > 1): ?>
                <?php if (!empty($search)): ?>
                    <a href="?page=blog&action=get&pageNo=<?= $pageNum - 1 ?>&search=<?= urlencode($search) ?>">Previous</a>
                <?php else: ?>
                    <a href="?page=blog&action=get&pageNo=<?= $pageNum - 1 ?>">Previous</a>
                <?php endif; ?>
            <?php endif; ?>

            <span class="current-page"><?= $pageNum ?></span>

            <!-- Next Page Link -->
            <?php if (count($blogsArr) === $limit): ?>
                <?php if (!empty($search)): ?>
                    <a href="?page=blog&action=get&pageNo=<?= $pageNum + 1 ?>&search=<?= urlencode($search) ?>">Next</a>
                <?php else: ?>
                    <a href="?page=blog&action=get&pageNo=<?= $pageNum + 1 ?>">Next</a>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    <?php endif; ?>

</div>

</body>
</html>