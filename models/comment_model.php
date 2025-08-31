<?php

require_once __DIR__."/../helper/uuid_generator.php";

class CommentModel {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
            
        // Create Comments Table
        $commentTable = "CREATE TABLE IF NOT EXISTS COMMENTS(
            COMMENTID CHAR(36) PRIMARY KEY,
            BLOGID CHAR(36) NOT NULL,
            USERID CHAR(36) NOT NULL,
            COMMENT TEXT NOT NULL,
            CREATEDDATE DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (BLOGID) REFERENCES blogs(BLOGID) ON DELETE CASCADE,
            FOREIGN KEY (USERID) REFERENCES users(id) ON DELETE CASCADE
        );";

        $this->conn->query($commentTable);

        // Drop triggers if exist
        $this->conn->query("DROP TRIGGER IF EXISTS increment_comment_count");
        $this->conn->query("DROP TRIGGER IF EXISTS decrement_comment_count");

        // Create triggers
        $this->conn->query("
        CREATE TRIGGER increment_comment_count
        AFTER INSERT ON COMMENTS
        FOR EACH ROW
        BEGIN
            UPDATE blogs
            SET totalcomments = totalcomments + 1
            WHERE BLOGID = NEW.BLOGID;
        END;
        ");

        $this->conn->query("
        CREATE TRIGGER decrement_comment_count
        AFTER DELETE ON COMMENTS
        FOR EACH ROW
        BEGIN
            UPDATE blogs
            SET totalcomments = totalcomments - 1
            WHERE BLOGID = OLD.BLOGID;
        END;
        ");
    }

    // Fetch comments by blog ID
    public function get_comment_byblogid($blogid, $limit = 0, $offset = 0) {
        try {
            if ($limit > 0) {
                $stmt = $this->conn->prepare(
                    "SELECT * FROM COMMENTS WHERE BLOGID = ? ORDER BY CREATEDDATE DESC LIMIT ? OFFSET ?"
                );
                $stmt->bind_param('sii', $blogid, $limit, $offset);
            } else {
                $stmt = $this->conn->prepare(
                    "SELECT * FROM COMMENTS WHERE BLOGID = ? ORDER BY CREATEDDATE DESC"
                );
                $stmt->bind_param('s', $blogid);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);

        } catch (\Throwable $th) {
            error_log("Error get_comment_byblogid: " . $th->getMessage());
            return null;
        }
    }

    // Fetch all comments or single comment
    public function get_comments($commentid = '', $limit = 10, $offset = 0) {
        try {
            if ($commentid === '') {
                $stmt = $this->conn->prepare(
                    "SELECT * FROM COMMENTS ORDER BY CREATEDDATE DESC LIMIT ? OFFSET ?"
                );
                $stmt->bind_param('ii', $limit, $offset);
            } else {
                $stmt = $this->conn->prepare(
                    "SELECT * FROM COMMENTS WHERE COMMENTID = ?"
                );
                $stmt->bind_param('s', $commentid);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            return ($commentid === '') ? $result->fetch_all(MYSQLI_ASSOC) : $result->fetch_assoc();

        } catch (\Throwable $th) {
            error_log("Error get_comments: " . $th->getMessage());
            return null;
        }
    }

    // Insert comment
    public function insert_comment($blogid, $userid, $comment) {
        try {
            $commentid = Generate_UUID();
            $stmt = $this->conn->prepare(
                "INSERT INTO COMMENTS (COMMENTID, BLOGID, USERID, COMMENT) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param('ssss', $commentid, $blogid, $userid, $comment);
            return $stmt->execute() ? $commentid : null;
        } catch (\Throwable $th) {
            error_log("Error insert_comment: " . $th->getMessage());
            return null;
        }
    }

    // Update comment
    public function update_comment($commentid, $comment) {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE COMMENTS SET COMMENT = ? WHERE COMMENTID = ?"
            );
            $stmt->bind_param('ss', $comment, $commentid);
            return $stmt->execute() && $stmt->affected_rows > 0;
        } catch (\Throwable $th) {
            error_log("Error update_comment: " . $th->getMessage());
            return false;
        }
    }


    
// Fetch comments by User ID (with Blog Title)
public function get_comments_byuserid($userid, $limit = 10, $offset = 0) {
    try {
        $stmt = $this->conn->prepare(
            "SELECT C.COMMENTID, C.COMMENT, C.CREATEDDATE, 
                    B.BLOGID, B.TITLE AS BLOG_TITLE
             FROM COMMENTS C
             INNER JOIN BLOGS B ON C.BLOGID = B.BLOGID
             WHERE C.USERID = ?
             ORDER BY C.CREATEDDATE DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->bind_param('sii', $userid, $limit, $offset);

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);

    } catch (\Throwable $th) {
        error_log("Error get_comments_byuserid: " . $th->getMessage());
        return null;
    }
}



    

    // Delete comment
    public function delete_comment($commentid) {
        try {
            $stmt = $this->conn->prepare(
                "DELETE FROM COMMENTS WHERE COMMENTID = ?"
            );
            $stmt->bind_param("s", $commentid);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (\Throwable $th) {
            error_log("Error delete_comment: " . $th->getMessage());
            return false;
        }
    }
}

?>
