<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
<link rel="stylesheet" href="../assets/css/profile.css">

</head>
<body>
  
<header>
  <h1>Welcome to Your Profile</h1>
</header>

<main>
  <!-- User Details -->
  <section class="user-details">
    <div class="profile-pic">
<img src="../assets/images/default_profile_img.webp" alt="Profile Picture">
      <button type="button">Change Profile Picture</button>
    </div>
    <div class="user-info">
      <h2><?php echo htmlspecialchars($user['username']); ?></h2>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
      <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
      <p><strong>Total Comments:</strong> 100</p>
      <p><strong>Total Blogs:</strong> 20</p>
      <p><strong>Created At:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
    </div>
  </section>

  <!-- User Comments -->
  <section class="comment-section">
    <h2>Your Comments</h2>
    <div class="comment-card">
      <p><strong>Comment:</strong> Habibi Come To Dubai</p>
      <p><strong>Blog Title:</strong> This is the To Anfare</p>
      <button onclick="confirmDelete('comment')">Delete Comment</button>
    </div>
    <div class="comment-card">
      <p><strong>Comment:</strong> Habibi Come To Dubai</p>
      <p><strong>Blog Title:</strong> This is the To Anfare</p>
      <button onclick="confirmDelete('comment')">Delete Comment</button>
    </div>
  </section>

  <!-- User Blogs -->
  <section class="blog-section">
    <h2>Your Blogs</h2>
    <div class="blog-card">
      <h3>Life Is Too Beautiful, Don't Waste on Timepass</h3>
      <p>I love to explore new things and like to meet new people....</p>
      <a href="#">Read Blog</a>
      <button onclick="confirmDelete('blog')">Delete Blog</button>
    </div>
    <div class="blog-card">
      <h3>Life Is Too Beautiful, Don't Waste on Timepass</h3>
      <p>I love to explore new things and like to meet new people....</p>
      <a href="#">Read Blog</a>
      <button onclick="confirmDelete('blog')">Delete Blog</button>
    </div>
  </section>

</main>

<footer>
  <p>&copy; 2025 Travel Blogging Platform</p>
</footer>

<script>
  function confirmDelete(type) {
    let message = '';
    if(type === 'blog') message = 'Do you really want to delete this blog?';
    else if(type === 'comment') message = 'Do you really want to delete this comment?';
    
    if(confirm(message)) {
      alert(`${type.charAt(0).toUpperCase() + type.slice(1)} deleted successfully!`);
      // Here you can add actual delete logic with AJAX or form submit
    }
  }
</script>

</body>
</html>
