<?php
require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../services/user_services.php";

// $id = $_GET['id'] ?? $_SESSION['userid'];
$userService = new UserService($conn);
$user = $userService->getUserById($id);


$isLoggedIn = isset($_SESSION['userid']);


$uploadDir = __DIR__ . "/../../media/profile_images/";

if (isset($_POST['deleteBlog'])) {
    $blogid = $_POST['blogid'];
    $blogService->deleteBlog($blogid, $id);
    $_SESSION['message'] = "Blog deleted successfully!";
    header("Location: index.php?page=users&id=$id");
    exit;
}

if (isset($_POST['deleteComment'])) {
    $commentid = $_POST['commentid'];
    $commentService->deleteComment($commentid, $id);
    $_SESSION['message'] = "Comment deleted successfully!";
    header("Location: index.php?page=users&id=$id");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['deleteBlog']) && !isset($_POST['deleteComment'])) {
    $userid = $_POST['userid'];
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $imagePath = null;

    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === 0) {
        $filename = uniqid() . '_' . basename($_FILES['profile_img']['name']);
        $targetFile = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['profile_img']['tmp_name'], $targetFile)) {
            $imagePath = $filename;
        }
    }

    $result = $userService->updateUser($userid, $username, $phone_number, $imagePath);
    $_SESSION['message'] = $result;

    $user = $userService->getUserById($userid);

    header("Location: index.php?page=users&id=$userid");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - <?php echo htmlspecialchars($user['username']); ?></title>
    <link rel="stylesheet" href="../assets/css/profile.css">
    <style>
        .success-message {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: #fff;
            padding: 14px 20px;
            margin: 20px auto;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 500;
            width: fit-content;
            max-width: 80%;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            animation: slideDown 0.6s ease, fadeOut 6s ease forwards;
            position: relative;
        }

        .success-message .checkmark {
            font-size: 20px;
            font-weight: bold;
            background: white;
            color: #4CAF50;
            border-radius: 50%;
            padding: 3px 6px;
            display: inline-block;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateY(-10px);
                visibility: hidden;
            }
        }
    
    </style>

</head>
<body>

<header class="navbar">
    <nav>
        <h3 class="logo">Travelogue</h3>
        <ul>
            <li><a href="?page=home&action=get">Home</a></li>
            <?php if ($isLoggedIn): ?>
                <li>
                    <button class="user-profile-btn" 
                            data-user-id="<?= htmlspecialchars($_SESSION['userid']) ?>">
                        PROFILE
                    </button>
                </li>
                <li>
                    <button id="logout-btn-id" class="user-profile-btn">LOGOUT</button>
                </li>
            <?php else: ?>
                <li><button class="login-btn">Login</button></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
<?php if (isset($_SESSION['message'])): ?>
    <div class="success-message">
        <span class="checkmark">✔</span>
        <?php 
        echo htmlspecialchars($_SESSION['message']); 
        unset($_SESSION['message']);
        ?>
    </div>
<?php endif; ?> 

    <section class="user-details">
        <div class="profile-pic">
            <img 
                src="<?php echo (!empty($user['image'])) 
                    ? '../media/profile_images/' . htmlspecialchars($user['image']) 
                    : '../assets/images/default_profile_img.webp'; ?>" 
                alt="Profile Picture" 
                id="profileImage">
        </div>
        
        <div class="user-info">
            <h2 id="usernameDisplay"><?php echo htmlspecialchars($user['username']); ?></h2>
            <p><strong>Email:</strong> <span id="emailDisplay"><?php echo htmlspecialchars($user['email']); ?></span></p>
            <p><strong>Phone:</strong> <span id="phoneDisplay"><?php echo htmlspecialchars($user['phone_number']); ?></span></p>
            <p><strong>Total Comments:</strong> <?php echo $comments ? count($comments) : '0'; ?></p>
            <p><strong>Total Blogs:</strong> <?php echo $blogs ? count($blogs) : '0'; ?></p>
            <button id="editProfileBtn">Edit Profile</button>
        </div>
    </section>

    <div id="editProfilePopup" class="popup">
        <div class="popup-content">
            <span class="close-btn" id="closePopup">&times;</span>
            <h2>Edit Profile</h2>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="userid" value="<?php echo $user['id']; ?>">

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

                <label for="phone_number">Phone:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>

                <label for="profile_img">Profile Image:</label>
                <input type="file" id="profile_img" name="profile_img" accept="image/*">

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>
<section class="comment-section">
    <h2>Your Comments</h2>
    <?php if (isset($comments) && !empty($comments)): ?>
        <?php foreach ($comments as $comment): ?>
            <div class="comment-card">
                <p><strong>Comment:</strong> <?= htmlspecialchars($comment['COMMENT']); ?></p>
                <p><strong>Blog Title:</strong> <?= htmlspecialchars($comment['BLOG_TITLE']); ?></p>
                <p><small><em>Posted on: <?= htmlspecialchars($comment['CREATEDDATE']); ?></em></small></p>
                
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="deleteComment" value="1">
                    <input type="hidden" name="commentid" value="<?= htmlspecialchars($comment['COMMENTID']); ?>">
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this comment?')">
                        Delete Comment
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="comment-card">
            <p><strong>No comments yet!</strong></p>
        </div>
    <?php endif; ?>
</section>


    <section class="blog-section">
        <h2>Your Blogs</h2>
        <div class="blog-container">
            <?php if (isset($blogs) && !empty($blogs)): ?>
                <?php foreach ($blogs as $blog): ?>
                    <?php
                    $images = [];
                    if (!empty($blog['IMAGES'])) {
                        $images = array_map('trim', explode(',', $blog['IMAGES']));
                    }
                    ?>
                    <div class="blog-card" data-blog-id="<?= htmlspecialchars($blog['BLOGID']) ?>">
                        <div class="blog-slider">
                            <div class="blog-images-container">
                                <?php if (!empty($images)): ?>
                                    <?php foreach ($images as $index => $img): ?>
                                        <img src="<?= htmlspecialchars('../media/blog_images/' . $img) ?>" alt="Blog Image <?= $index + 1 ?>">
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/600x300?text=No+Image" alt="Placeholder">
                                <?php endif; ?>
                            </div>
                            
                            <?php if (count($images) > 1): ?>
                                <button class="image-nav-arrows prev-arrow" onclick="previousImage(this)">‹</button>
                                <button class="image-nav-arrows next-arrow" onclick="nextImage(this)">›</button>
                                <div class="image-nav">
                                    <?php for ($i = 0; $i < count($images); $i++): ?>
                                        <span class="image-dot <?= $i === 0 ? 'active' : '' ?>" onclick="goToImage(this, <?= $i ?>)"></span>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="blog-content">
                            <h2 class="blog-title"><?= htmlspecialchars($blog['TITLE']) ?></h2>
                            <p class="blog-meta">
                                By <span><?= htmlspecialchars($user['username']) ?></span> • 
                                <?= date("F j, Y", strtotime($blog['CREATEDDATE'])) ?>
                            </p>
                            <p class="blog-excerpt">
                                <?= htmlspecialchars(substr($blog['SHORTDESC'], 0, 120)) . '...' ?>
                            </p>

                            <div class="blog-buttons">
                                <a href="?page=blog&action=getbyid&id=<?= htmlspecialchars($blog['BLOGID']) ?>" class="read-more-btn">Read More</a>
                                
                                <form method="POST" onsubmit="return confirmDelete('blog')">
                                    <input type="hidden" name="deleteBlog" value="1">
                                    <input type="hidden" name="blogid" value="<?= htmlspecialchars($blog['BLOGID']) ?>">
                                    <button type="submit" class="delete-btn">🗑️ Delete Blog</button>
                                </form>
                                
                            </div>
                            
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>You haven't written any blogs yet. Start sharing your travel experiences!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>


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

<script>
    function confirmDelete(type) {
        let message = '';
        if (type === 'blog') {
            message = 'Do you really want to delete this blog? This action cannot be undone.';
        } else if (type === 'comment') {
            message = 'Do you really want to delete this comment? This action cannot be undone.';
        }
        
        return confirm(message);
    }

    const editBtn = document.getElementById('editProfileBtn');
    const popup = document.getElementById('editProfilePopup');
    const closeBtn = document.getElementById('closePopup');

    if (editBtn && popup && closeBtn) {
        editBtn.addEventListener('click', () => {
            popup.style.display = 'block';
        });

        closeBtn.addEventListener('click', () => {
            popup.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === popup) {
                popup.style.display = 'none';
            }
        });
    }

    const editForm = document.querySelector('#editProfilePopup form');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const phone = document.getElementById('phone_number').value.trim();
            
            if (username.length < 3) {
                alert('Username must be at least 3 characters long.');
                e.preventDefault();
                return;
            }
            
            if (phone && !/^\d{10,15}$/.test(phone.replace(/[-\s\(\)]/g, ''))) {
                alert('Please enter a valid phone number.');
                e.preventDefault();
                return;
            }
        });
    }

    function nextImage(button) {
        const blogCard = button.closest('.blog-card');
        const container = blogCard.querySelector('.blog-images-container');
        const images = container.querySelectorAll('img');
        const dots = blogCard.querySelectorAll('.image-dot');
        
        let currentIndex = parseInt(blogCard.dataset.currentImage || '0');
        currentIndex = (currentIndex + 1) % images.length;
        
        container.style.transform = `translateX(-${currentIndex * 100}%)`;
        blogCard.dataset.currentImage = currentIndex;
        
        updateDots(dots, currentIndex);
    }

    function previousImage(button) {
        const blogCard = button.closest('.blog-card');
        const container = blogCard.querySelector('.blog-images-container');
        const images = container.querySelectorAll('img');
        const dots = blogCard.querySelectorAll('.image-dot');
        
        let currentIndex = parseInt(blogCard.dataset.currentImage || '0');
        currentIndex = currentIndex === 0 ? images.length - 1 : currentIndex - 1;
        
        container.style.transform = `translateX(-${currentIndex * 100}%)`;
        blogCard.dataset.currentImage = currentIndex;
        
        updateDots(dots, currentIndex);
    }

    function goToImage(dot, index) {
        const blogCard = dot.closest('.blog-card');
        const container = blogCard.querySelector('.blog-images-container');
        const dots = blogCard.querySelectorAll('.image-dot');
        
        container.style.transform = `translateX(-${index * 100}%)`;
        blogCard.dataset.currentImage = index;
        
        updateDots(dots, index);
    }

    function updateDots(dots, activeIndex) {
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === activeIndex);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const blogCards = document.querySelectorAll('.blog-card');
        blogCards.forEach(card => {
            card.dataset.currentImage = '0';
        });

        const logoutBtn = document.querySelector('#logout-btn-id');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                window.location.href = "?page=auth&action=logout";
                
            });
        }
    });
</script>

</body>
</html>