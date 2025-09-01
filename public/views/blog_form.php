<?php
require_once __DIR__."/../../services/blogService.php";
require_once __DIR__."/../../fileuploader/LocalUploader.php";

// Directory to store uploaded images
$uploadDir = __DIR__ . "/../../media/blog_images/";
$maxSize = 1 * 1024 * 1024;
$allowedExt = ['jpg', 'jpeg', 'png'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $userid  =  $_SESSION['userid'] ?? null;
    $title     = $_POST['title'] ?? null;
    $shortdesc = $_POST['shortdesc'] ?? null;
    $content   = $_POST['content'] ?? null;

    $imageFiles = $_FILES['images'] ?? null;
    $uploader = new LoacalUploader();

    $savedFilenames = $uploader->fileUpload($imageFiles, $uploadDir, $maxSize, $allowedExt) ?? [];

    // if ($imageFiles && isset($imageFiles['name'])) {
    //     foreach ($imageFiles['name'] as $key => $name) {
    //         $tmpName = $imageFiles['tmp_name'][$key];
    //         $size    = $imageFiles['size'][$key];
    //         $error   = $imageFiles['error'][$key];

    //         $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    //         if (($ext === 'jpg' || $ext === 'jpeg' || $ext === 'png') && $size <= 1 * 1024 * 1024) { // 1 MB
    //             $newName = uniqid() . "." . $ext;
    //             $destination = $uploadDir . $newName;

    //             if (move_uploaded_file($tmpName, $destination)) {
    //                 $savedFilenames[] = $newName;
    //             }
    //         }
    //     }
    // }

    $imageString = implode(',', $savedFilenames);

    $blogService->createBlog($userid, $title, $shortdesc, $content, $imageString);

    header("Location: index.php?page=blog&action=get");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Blog</title>
<link rel="stylesheet" href="../assets/css/blog_form.css">
</head>
<body>

<script>
function limitFiles(input, maxFiles) {
    if (input.files.length > maxFiles) {
        alert(`You can only upload a maximum of ${maxFiles} images.`);
        input.value = '';
    }
}
</script>

<div class="container">
    <h2>Create Your Blog</h2>
    <form action="" method="post" enctype="multipart/form-data">


        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" placeholder="✨ What's your story about? (e.g., 'My Epic Adventure in Tokyo')" required>
        </div>

        <div class="form-group">
            <label for="shortdesc">Short Description:</label>
            <input type="text" name="shortdesc" id="shortdesc" placeholder="🎯 Hook your readers in one compelling sentence!" required>
        </div>

        <div class="form-group">
            <label for="content">Content:</label>
            <textarea name="content" id="content" rows="8" placeholder="📝 Pour your heart out here... Share your thoughts, experiences, tips, or stories that will captivate your audience. What unique perspective do you bring to the world?" required></textarea>
        </div>

        <div class="form-group">
            <label for="images">Upload Images <span class="note">(JPEG/JPG/PNG, max 1MB each, up to 2 images)</span>:</label>
            <input type="file" name="images[]" id="images" multiple accept=".jpg,.jpeg" onchange="limitFiles(this, 2)">
        </div>

        <button type="submit">Create Blog</button>
    </form>
</div>

</body>
</html>