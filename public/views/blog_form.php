<?php

require_once __DIR__."../../services/blog_services.php";

// If you want to process after submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid    = $_POST['userid'] ?? null;
    $title     = $_POST['title'] ?? null;
    $shortdesc = $_POST['shortdesc'] ?? null;
    $content   = $_POST['content'] ?? null;

    // Example: save to DB (pseudo-code)
    // $blogService->createBlog($userid, $title, $shortdesc, $content);

    echo "<h3>Form Submitted</h3>";
    echo "UserID: $userid <br>";
    echo "Title: $title <br>";
    echo "Short Desc: $shortdesc <br>";
    echo "Content: <p>$content</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog</title>
</head>
<body>
    <div>
        <h2>Create Blog</h2>
        <form action="" method="post">
            
            <!-- User ID (hidden, or text if you want to input manually) -->
            <input type="hidden" name="userid" value="<?php echo $_SESSION['userid'] ?? 1; ?>">

            <!-- Blog Title -->
            <label for="title">Title:</label><br>
            <input type="text" name="title" id="title" required><br><br>

            <!-- Short Description -->
            <label for="shortdesc">Short Description:</label><br>
            <input type="text" name="shortdesc" id="shortdesc" required><br><br>

            <!-- Blog Content -->
            <label for="content">Content:</label><br>
            <textarea name="content" id="content" rows="8" cols="50" required></textarea><br><br>

            <!-- Submit Button -->
            <button type="submit">Create Blog</button>
        </form>
    </div>
</body>
</html>