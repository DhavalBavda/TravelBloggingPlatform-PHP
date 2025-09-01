<?php
$isLoggedIn = isset($_SESSION['userid']);
$blogId = $_GET['id'] ?? null;

if (!$blogId) {
    echo "Blog ID not specified.";
    exit;
}

// Fetch single blog from service
$blog = $blogService->getBlogById($blogId);
if (!$blog) {
    echo "Blog not found.";
    exit;
}

$rawDate = $blog['CREATEDDATE'];
$dateObj = new DateTime($rawDate);
$formattedDate = $dateObj->format('F j, Y • g:i A');

$images = [];
if (!empty($blog['IMAGES'])) {
    $images = array_map('trim', explode(',', $blog['IMAGES']));
}

// Fetch comments for this blog
$comments = $commentService->getCommentsByBlog($blogId);

function formatBlogContent($content) {
    // Convert literal "\n" to real newlines
    $content = str_replace('\n', "\n", $content);

    // Escape HTML to prevent XSS
    $safeContent = htmlspecialchars($content);

    // Normalize line endings
    $safeContent = str_replace(["\r\n", "\r"], "\n", $safeContent);

    // Split by 2 or more consecutive line breaks
    $paragraphs = preg_split("/\n{2,}/", $safeContent);

    // Wrap each paragraph in <p> tags, replacing single line breaks with <br>
    $formatted = '';
    foreach ($paragraphs as $para) {

        $para = nl2br(trim($para));
        if ($para === '') continue;
        // Replace single line breaks with <br>
        $para = nl2br($para);
        $formatted .= "<p>{$para}</p>\n";

    }

    return $formatted;
}

// Handle new comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    if ($isLoggedIn) {
        $commentText = trim($_POST['comment']);
        if ($commentText !== '') {
            $commentService->addComment($blogId, $_SESSION['userid'], $commentText);
            // Refresh the page to show new comment
            header("Location: ?page=blog&action=getbyid&id=$blogId");
            exit;
        }
    } else {
        $commentError = "You must be logged in to comment.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($blog['TITLE']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Dancing+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/blog_listing.css">

    <style>
        /* Full blog content style */
        .full-blog-content {
            max-width: 800px;
            margin: 40px auto;
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
        }

        .full-blog-content {
            max-width: 800px;
            margin: 40px auto;
            font-size: 1.125rem;
            line-height: 1.75;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }

        /* Paragraphs */
        .full-blog-content p {
            margin: 1.2em 0;
            line-height: 1.8;
            font-size: 1.1rem;
            color: #444;
        }

        /* Headings inside content */
        .full-blog-content h2 {
            font-size: 2rem;
            margin: 2em 0 1em 0;
            font-weight: 600;
            color: #46816b;
        }

        .full-blog-content h3 {
            font-size: 1.5rem;
            margin: 1.5em 0 0.8em 0;
            font-weight: 600;
            color: #46816b;
        }

        /* Inline images inside content */
        .full-blog-content img {
            max-width: 100%;
            display: block;
            margin: 20px auto;
            border-radius: 12px;
        }

        /* Blockquotes */
        .full-blog-content blockquote {
            border-left: 4px solid #46816b;
            margin: 20px 0;
            padding-left: 16px;
            color: #555;
            font-style: italic;
        }

        /* Links */
        .full-blog-content a {
            color: #46816b;
            text-decoration: underline;
        }

        .full-blog-content a:hover {
            color: #35604f;
            text-decoration: none;
        }

        .blog-slider-full {
            position: relative;
            width: 100%;
            max-width: 900px;
            height: 450px;
            margin: 40px auto;
            overflow: hidden;
            border-radius: 12px;
        }

        .blog-slider-full img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 100%;
            opacity: 0;
            transition: left 0.5s ease, opacity 0.5s ease;
        }

        .blog-slider-full img.active {
            left: 0;
            opacity: 1;
        }

        .back-btn {
            display: block;
            width: 180px;
            margin: 40px auto;
            padding: 12px;
            background-color: #333;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
        }
        .comments-section {
            max-width: 800px;
            margin: 40px auto;
            background-color: #fefefe;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .comments-section h2 {
            font-size: 1.6rem;
            color: #46816b;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .comments-list {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 300px; /* fixed height */
            overflow-y: auto;  /* vertical scroll */
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .comment-item {
            border-bottom: 1px solid #ddd;
            padding: 12px 0;
        }

        .comment-author {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .comment-date {
            font-size: 0.875rem;
            color: #888;
            margin-bottom: 5px;
        }

        .comment-text {
            color: #555;
            line-height: 1.5;
        }

        .comment-form {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .comment-form textarea {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 1rem;
            resize: vertical;
            margin-bottom: 12px;
        }

        .comment-form button {
            padding: 10px 18px;
            background-color: #46816b;
            color: #fff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .comment-form button:hover {
            background-color: #35604f;
        }

    </style>

    <script>
        const isLoggedIn = <?= json_encode($isLoggedIn) ?>;

        document.addEventListener('DOMContentLoaded', () => {
            // Profile / login button logic
            if (isLoggedIn) {
                const profileBtn = document.querySelector('.user-profile-btn');
                if (profileBtn) {
                    profileBtn.addEventListener('click', e => {
                        e.preventDefault();
                        const userId = profileBtn.getAttribute('data-user-id');
                        if (userId) window.location.href = `index.php?page=users&id=${userId}`;
                        else alert("User ID not found.");
                    });
                }
                
                const logoutBtn = document.querySelector('#logout-btn-id');
                if (logoutBtn) {
                    logoutBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        window.location.href = "?page=auth&action=logout";
                        
                    });
                }
            } else {
                const loginBtn = document.querySelector('.login-btn');
                if (loginBtn) {
                    loginBtn.addEventListener('click', e => {
                        e.preventDefault();
                        window.location.href = "index.php?page=auth&action=login";
                    });
                }
            }

            // Full blog image slider
            const slider = document.querySelector('.blog-slider-full');
            if (slider) {
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
            }
        });
    </script>
</head>
<body>

    <header class="navbar">
        <nav class = "navbar-container">
            
            <div class="navbar-brand">
                <a href="?page=home&action=get">Travelogue</a>
            </div>
            
            <ul class="navbar-links">
                <li><a href="?page=home&action=get">Home</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><button class="user-profile-btn" data-user-id="<?= htmlspecialchars($_SESSION['userid']) ?>">PROFILE</button></li>
                    <li><button id="logout-btn-id" class="user-profile-btn">LOGOUT</button></li>
                <?php else: ?>
                    <li><button class="login-btn">Login</button></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <h1><?= htmlspecialchars($blog['TITLE']) ?></h1>
        <p>By <?= htmlspecialchars($blog['author_name']) ?> • <?= $formattedDate ?></p>
    </section>

    <!-- Blog Slider -->
    <div class="blog-slider-full">
        <?php foreach($images as $img): ?>
            <img src="<?= htmlspecialchars('../media/blog_images/' . $img) ?>" alt="Blog Image">
        <?php endforeach; ?>
    </div>

    <section class="container full-blog-content">
        <?= formatBlogContent($blog['CONTENT']) ?>
    </section>

    <!-- Comments Section -->
    <section class="container comments-section">
        <h2>Comments (<?= $blog['TOTALCOMMENTS'] ?>)</h2>

        <ul class="comments-list">
            <?php if (count($comments) > 0): ?>
                <?php foreach($comments as $c): ?>
                    <?php 
                        $commentDate = new DateTime($c['CREATEDDATE']);
                        $formattedCommentDate = $commentDate->format('M j, Y • g:i A');
                    ?>
                    <li class="comment-item">
                        <div class="comment-author"><?= htmlspecialchars($c['USERNAME']) ?>:</div>
                        <div class="comment-date"><?= $formattedCommentDate ?></div>
                        <div class="comment-text"><?= nl2br(htmlspecialchars($c['COMMENT'])) ?></div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php endif; ?>
        </ul>

        <!-- Comment Form -->
        <?php if ($isLoggedIn): ?>
            <form method="POST" class="comment-form">
                <textarea name="comment" placeholder="Write your comment..." required></textarea>
                <button type="submit">Post Comment</button>
            </form>
        <?php else: ?>
            <p><a href="index.php?page=auth&action=login">Login</a> to post a comment.</p>
        <?php endif; ?>
    </section>

    <a href="?page=blog&action=get" class="back-btn">← Back to Blogs</a>

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