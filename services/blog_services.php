<?php
require_once __DIR__ . '/../models/blog_model.php';
require_once __DIR__ . '/../services/user_services.php';

class BlogService {
    private $blogModel;
    private $userModel;

    public function __construct($conn) {
        $this->blogModel = new BlogModel($conn);
        $this->userModel = new UserService($conn);
    }

   
    public function createBlog($userid, $title, $shortdesc, $content, $images = '') {
        return $this->blogModel->insert_blog($userid, $title, $shortdesc, $content, $images);
    }

    
    // public function getUserById($id) {
    //     return $this->blogModel->get_user($id);
    // }

    public function getAllBlogs($blogid = '', $limit = 10, $offset = 0){
        $allblog  = $this->blogModel->get_blogs($blogid, $limit, $offset);
        $allUsers = $this->userModel->getAllUsers();


        // Build a quick lookup table of users by ID for faster access
        $userMap = [];
        foreach ($allUsers as $user) {
            $userMap[$user['id']] = $user['username']; // You can also store the full user if needed
        }

        // Attach username to each blog
        foreach ($allblog as &$blog) {
            $authorId = $blog['AUTHORID'];

            $blog['author_name'] = $userMap[$authorId] ?? 'Unknown';
        }

        return $allblog ;
    }

    public function updateBlog($blogid, $title, $shortdesc, $content, $images) {
        return $this->blogModel->update_blog($blogid, $title, $shortdesc, $content, $images);
    }
}
?>
