<?php
require_once __DIR__ . "/../models/comment_model.php";
require_once __DIR__ . '/../services/user_services.php';

class CommentService {

    private $commentModel;
    private $userService;

    public function __construct($conn) {
        $this->commentModel = new CommentModel($conn);
        $this->userService = new UserService($conn);
    }

    // Get comments of a specific blog
    public function getCommentsByBlog($blogid, $limit = 0, $offset = 0) {
        if (empty($blogid)) {
            throw new Exception("Blog ID is required.");
        }
        
        $allComment = $this->commentModel->get_comment_byblogid($blogid, $limit, $offset);
        $allUsers = $this->userService->getAllUsers();

        // Build a quick lookup table of users by ID for faster access
        $userMap = [];
        foreach ($allUsers as $user) {
            $userMap[$user['id']] = $user['username']; // You can also store the full user if needed
        }

        // Attach username to each blog
        foreach ($allComment as &$comment) {
            $authorId = $comment['USERID'];

            $comment['USERNAME'] = $userMap[$authorId] ?? 'Unknown';
        }

        return $allComment;

    }

    // Get all comments or single comment
    public function getComments($commentid = '', $limit = 10, $offset = 0) {
        return $this->commentModel->get_comments($commentid, $limit, $offset);
    }

    // Add new comment
    public function addComment($blogid, $userid, $comment) {
        if (empty($comment) || strlen(trim($comment)) < 1) {
            throw new Exception("Comment cannot be empty.");
        }

        return $this->commentModel->insert_comment($blogid, $userid, trim($comment));
    }

    // Update comment
    public function updateComment($commentid, $comment, $userid) {
        if (empty($commentid)) {
            throw new Exception("Comment ID is required.");
        }

        if (empty($comment) || strlen(trim($comment)) < 1) {
            throw new Exception("Comment cannot be empty.");
        }

        // Optional: You could add an ownership check here
        // Example: only allow user who wrote it to update
        return $this->commentModel->update_comment($commentid, trim($comment));
    }

    // Delete comment
    public function deleteComment($commentid, $userid = null) {
        if (empty($commentid)) {
            throw new Exception("Comment ID is required.");
        }

        // Optional: You can check if $userid matches owner before deleting
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
