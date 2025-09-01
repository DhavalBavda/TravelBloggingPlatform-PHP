<?php
require_once __DIR__ . '/../models/Blog.php';
require_once __DIR__ . '/../services/userService.php';

class BlogService {
    private $blogModel;
    private $userService;

    public function __construct($conn) {
        $this->blogModel = new BlogModel($conn);
        $this->userService = new UserService($conn);
    }

   
    public function createBlog($userid, $title, $shortdesc, $content, $images = '') {
        return $this->blogModel->insert_blog($userid, $title, $shortdesc, $content, $images);
    }

     public function getBlogsByUserId($userid, $limit = 0, $offset = 0) {
        return $this->blogModel->get_blogs_byuserid($userid, $limit, $offset);
    }

    public function deleteBlog($blogid) {
        return $this->blogModel->delete_blog($blogid);
    }
    
    public function getBlogById($id) {
        $allblog = $this->blogModel->get_blogs($id);
        
        $authorId = $allblog['AUTHORID'];
        if($authorId){
            $allUsers = $this->userService->getUserById($authorId);
            $allblog['author_name'] = $allUsers['username'] ?? 'Unknown';
        }
        else{
            $allblog['author_name'] = 'Unknown';
        }
        return $allblog;
    }

    public function getAllBlogs($blogid = '', $limit = 10, $offset = 0, $search = ''){
        return $this->blogModel->get_blogs($blogid, $limit, $offset, $search);
    }

    public function updateBlog($blogid, $title, $shortdesc, $content, $images) {
        return $this->blogModel->update_blog($blogid, $title, $shortdesc, $content, $images);
    }
}
?>
