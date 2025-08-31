<?php
require_once __DIR__ . '/../models/blog_model.php';

class BlogService {
    private $blogModel;

    public function __construct($conn) {
        $this->blogModel = new BlogModel($conn);
    }

   
    public function createBlog($userid, $title, $shortdesc, $content, $images = '') {
        return $this->blogModel->insert_blog($userid, $title, $shortdesc, $content, $images);
    }

    
    // public function getUserById($id) {
    //     return $this->blogModel->get_user($id);
    // }

    // Get blogs by user id
    public function getBlogsByUserId($userid, $limit = 0, $offset = 0) {
        return $this->blogModel->get_blogs_byuserid($userid, $limit, $offset);
    }

        public function deleteBlog($blogid) {
        return $this->blogModel->delete_blog($blogid);
    }

    
    public function getblogById($id) {
        return $this->blogModel->get_user($id);
    }

    public function getAllBlogs($blogid = '', $limit = 10, $offset = 0){
        $allblog  = $this->blogModel->get_blogs($blogid, $limit, $offset);
        return $allblog ;
    }

    public function updateBlog($blogid, $title, $shortdesc, $content, $images) {
        return $this->blogModel->update_blog($blogid, $title, $shortdesc, $content, $images);
    }
}
?>
