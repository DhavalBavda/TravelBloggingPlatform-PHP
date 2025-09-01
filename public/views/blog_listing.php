<?php
$isLoggedIn = isset($_SESSION['userid']);

$limit = 3; 
$pageNum = isset($_GET['pageNo']) ? max(1, intval($_GET['pageNo'])) : 1;
$offset = ($pageNum - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$blogsArr = $blogService->getAllBlogs('', $limit, $offset, $search);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Dancing+Script&display=swap" rel="stylesheet">
    <link rel ="stylesheet" href="../assets/css/blog_listing.css">

    <script>

        const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
        document.addEventListener('DOMContentLoaded', () => {

            const createBtn = document.querySelector('.create-blog-btn');  
            createBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (isLoggedIn) {
                    window.location.href = "index.php?page=blog&action=create";
                } else {
                    window.location.href = "index.php?page=auth&action=login";
                }
            });

            

            if (isLoggedIn) {
                const profileBtn = document.querySelector('.user-profile-btn');
                if (profileBtn) {
                    profileBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const userId = profileBtn.getAttribute('data-user-id');
                        if (userId) {
                            window.location.href = `index.php?page=users&id=${userId}`;
                        } else {
                            alert("User ID not found.");
                        }
                    });
                }

                const logoutBtn = document.querySelector('#logout-btn-id');
                if (logoutBtn) {
                    logoutBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        window.location.href = "?page=auth&action=logout";
                        
                    });
                }

            }
            else{
                const loginBtn = document.querySelector('.login-btn');
                loginBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = "index.php?page=auth&action=login";
                });
            }

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
                    }, 3000);
                } else if (images.length === 1) {
                    images[0].classList.add('active');
                }
            });
        });

    </script>

</head>
<body>

<header class="navbar">
    <nav class = "navbar-container">
        
        <div class="navbar-brand">
            <a href="?page=home&action=get">Travelogue</a>
        </div>

        <ul class = "navbar-links">
            <li><a href="?page=home&action=get">Home</a></li>
            <?php if ($isLoggedIn): ?>
                <li><button class="user-profile-btn" data-user-id="<?= htmlspecialchars($_SESSION['userid']) ?>" >PROFILE</button></li>
                <li>
                    <button id="logout-btn-id" class="user-profile-btn">LOGOUT</button>
                </li>
            <?php else: ?>
                <li><button class="login-btn">Login</button></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<section class="hero">
    <img src="../assets/images/Blog_head1.png">
    <p>Explore a world of creativity, inspiration, and shared experiences from our community of writers</p>
</section>

<section class="container">
    <a href="#" class="create-blog-btn">Create New Blog</a>

    <form class="search-bar" method="GET" action="">
        <input type="hidden" name="page" value="blog">
        <input type="hidden" name="action" value="get">
        <input type="text" name="search" placeholder="Search blogs..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>

    <div class="blog-grid">
        <?php if(!empty($blogsArr)): ?>
            <?php foreach($blogsArr as $blog): ?>
                <?php
                    $images = [];
                    if (!empty($blog['IMAGES'])) {
                        $images = array_map('trim', explode(',', $blog['IMAGES']));
                    }
                ?>
                <div class="blog-card">
                    <div class="blog-slider">
                        <?php foreach($images as $img): ?>
                            <img src="<?= htmlspecialchars('../media/blog_images/' . $img) ?>" alt="Blog Image">
                        <?php endforeach; ?>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">By <span><?= htmlspecialchars($blog['author_name']) ?></span> • <?= htmlspecialchars($blog['CREATEDDATE']) ?></div>
                        <h3><?= htmlspecialchars($blog['TITLE']) ?></h3>
                        <p><?= htmlspecialchars($blog['SHORTDESC']) ?></p>
                        <a href="?page=blog&action=getbyid&id=<?=$blog['BLOGID']?>" class="read-more">Read More</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-blogs">No blogs found.</div>
        <?php endif; ?>
    </div>

    <?php if (!empty($blogsArr)): ?>
        <div class="pagination">
            <?php if ($pageNum > 1): ?>
                <?php if (!empty($search)): ?>
                    <a href="?page=blog&action=get&pageNo=<?= $pageNum - 1 ?>&search=<?= urlencode($search) ?>">Previous</a>
                <?php else: ?>
                    <a href="?page=blog&action=get&pageNo=<?= $pageNum - 1 ?>">Previous</a>
                <?php endif; ?>
            <?php endif; ?>

            <span class="current-page"><?= $pageNum ?></span>

            <?php if (count($blogsArr) === $limit): ?>
                <?php if (!empty($search)): ?>
                    <a href="?page=blog&action=get&pageNo=<?= $pageNum + 1 ?>&search=<?= urlencode($search) ?>">Next</a>
                <?php else: ?>
                    <a href="?page=blog&action=get&pageNo=<?= $pageNum + 1 ?>">Next</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</section>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-about">
            <h3>Travelogue</h3>
            <p>Inspiring journeys & untold stories.</p>
        </div>
        <div class="footer-links">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="?page=home&action=get">Home</a></li>
                <li><a href="?page=blog&action=get">Blogs</a></li>
            </ul>
        </div>
        <div class="footer-social">
            <h4>Follow Us</h4>
            <a href="#" title="Website">🌍</a>
            <a href="#" title="Instagram">📸</a>
            <a href="#" title="Twitter">🐦</a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2025 Travelogue. All Rights Reserved.</p>
    </div>
</footer>



</body>
</html>