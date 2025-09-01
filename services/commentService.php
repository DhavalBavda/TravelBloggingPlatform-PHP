<?php
require_once __DIR__ . "/../models/Comment.php";
require_once __DIR__ . '/../services/userService.php';

class CommentService {

    private $commentModel;
    private $userService;

    public function __construct($conn) {
        $this->commentModel = new Comment($conn);
        $this->userService = new UserService($conn);
    }

     public function getCommentsByBlog($blogid, $limit = 0, $offset = 0) {
        if (empty($blogid)) {
            throw new Exception("Blog ID is required.");
        }
        
        $allComment = $this->commentModel->get_comment_byblogid($blogid, $limit, $offset);
        $allUsers = $this->userService->getAllUsers();

         $userMap = [];
        foreach ($allUsers as $user) {
            $userMap[$user['id']] = $user['username']; 
        }

         foreach ($allComment as &$comment) {
            $authorId = $comment['USERID'];

            $comment['USERNAME'] = $userMap[$authorId] ?? 'Unknown';
        }

        return $allComment;

    }

     public function getComments($commentid = '', $limit = 10, $offset = 0) {
        return $this->commentModel->get_comments($commentid, $limit, $offset);
    }

     public function addComment($blogid, $userid, $comment) {
        if (empty($comment) || strlen(trim($comment)) < 1) {
            throw new Exception("Comment cannot be empty.");
        }

        return $this->commentModel->insert_comment($blogid, $userid, trim($comment));
    }

     public function updateComment($commentid, $comment, $userid) {
        if (empty($commentid)) {
            throw new Exception("Comment ID is required.");
        }

        if (empty($comment) || strlen(trim($comment)) < 1) {
            throw new Exception("Comment cannot be empty.");
        }

       
        return $this->commentModel->update_comment($commentid, trim($comment));
    }

     public function deleteComment($commentid, $userid = null) {
        if (empty($commentid)) {
            throw new Exception("Comment ID is required.");
        }

         return $this->commentModel->delete_comment($commentid);
    }


    
public function getCommentsByUser($userid, $limit = 10, $offset = 0) {
    if (empty($userid)) {
        throw new Exception("User ID is required.");
    }
    return $this->commentModel->get_comments_byuserid($userid, $limit, $offset);
}

}
?>
