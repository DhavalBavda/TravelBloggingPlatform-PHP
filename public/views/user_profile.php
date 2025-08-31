<?php

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../services/user_services.php";

// $id = $_GET['id'] ?? $_SESSION['userid'];
$userService = new UserService($conn);
$user = $userService->getUserById($id);

$uploadDir = __DIR__ . "/../../media/profile_images/";

// Handle update inside this page
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = $_POST['userid'];
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $imagePath = null;

    // Handle image upload
    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === 0) {
        $filename = uniqid() . '_' . basename($_FILES['profile_img']['name']);
        $targetFile = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['profile_img']['tmp_name'], $targetFile)) {
            $imagePath = $filename;
        }
    }

    // Update user
    $result = $userService->updateUser($userid, $username, $phone_number, $imagePath);
    $_SESSION['message'] = $result;

    // Refresh user data
    $user = $userService->getUserById($userid);

    // Redirect to avoid form resubmission
    header("Location: index.php?page=users&id=$userid");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <link rel="stylesheet" href="../assets/css/profile.css">
  <style>
    /* Popup Styles */
    .popup {
      display: none;
      position: fixed;
      z-index: 100;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.5);
    }

    .popup-content {
      background-color: #fff;
      margin: 10% auto;
      padding: 20px;
      width: 90%;
      max-width: 400px;
      border-radius: 10px;
      position: relative;
    }

    .close-btn {
      position: absolute;
      top: 10px; right: 15px;
      font-size: 25px;
      cursor: pointer;
    }

    .popup-content input {
      width: 100%;
      padding: 8px;
      margin: 8px 0;
    }

    .popup-content button {
      padding: 10px 15px;
      margin-top: 10px;
    }
    #editProfileBtn {
  background: linear-gradient(135deg, #615359ff, #381118ff, #ff6a88);
  color: white;
  border: none;
  padding: 12px 28px;
  font-size: 16px;
  font-weight: bold;
  border-radius: 50px;
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(36, 5, 20, 0.4);
  transition: all 0.3s ease-in-out;
}

#editProfileBtn:hover {
  background: linear-gradient(135deg, #673c43ff, #3b2b32ff, #ecd3cfff);
  box-shadow: 0 6px 20px rgba(95, 78, 87, 0.6);
  transform: translateY(-2px) scale(1.05);
}

#editProfileBtn:active {
  transform: scale(0.95);
  box-shadow: 0 3px 10px rgba(75, 119, 169, 0.4);
}
  </style>
</head>
<body>
  
<header>
  <h1>Welcome to Your Profile</h1>
</header>

<main>
  <!-- User Details -->
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
      <p><strong>Total Comments:</strong> 100</p>
      <p><strong>Total Blogs:</strong> 20</p>
      <button id="editProfileBtn">✏️ Edit Profile</button>
    </div>
  </section>

  <!-- Edit Profile Popup -->
<div id="editProfilePopup" class="popup">
    <div class="popup-content">
        <span class="close-btn" id="closePopup">&times;</span>
        <h2>Edit Profile</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="userid" value="<?php echo $user['id']; ?>">

            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label>Phone:</label>
            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>

            <label>Profile Image:</label>
            <input type="file" name="profile_img" accept="image/*">

            <button type="submit">Save Changes</button>
        </form>
    </div>
</div>

  <!-- User Comments -->
  <section class="comment-section">
    <h2>Your Comments</h2>
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
  </section>
</main>

<footer>
  <p>&copy; 2025 Travel Blogging Platform</p>
</footer>

<script>
  // Confirm delete
  function confirmDelete(type) {
    let message = '';
    if(type === 'blog') message = 'Do you really want to delete this blog?';
    else if(type === 'comment') message = 'Do you really want to delete this comment?';
    
    if(confirm(message)) {
      alert(`${type.charAt(0).toUpperCase() + type.slice(1)} deleted successfully!`);
      // AJAX or form logic here
    }
  }

  // Edit Profile Popup
  const editBtn = document.getElementById('editProfileBtn');
  const popup = document.getElementById('editProfilePopup');
  const closeBtn = document.getElementById('closePopup');

  editBtn.addEventListener('click', () => popup.style.display = 'block');
  closeBtn.addEventListener('click', () => popup.style.display = 'none');

  // Close popup on outside click
  window.addEventListener('click', e => {
    if(e.target == popup) popup.style.display = 'none';
  });
</script>

</body>
</html>