<?php

include "../config/dbconfig.php";
include "../helper/uuid_generator.php";

$commentTable = "CREATE TABLE IF NOT EXISTS COMMENTS(
    COMMENTID CHAR(36) PRIMARY KEY,
    BLOGID CHAR(36) NOT NULL,
    USERID CHAR(36) NOT NULL,
    COMMENT TEXT NOT NULL,
    createddate DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (BLOGID) REFERENCES blogs(BLOGID) ON DELETE CASCADE,
    FOREIGN KEY (USERID) REFERENCES users(id) ON DELETE CASCADE
);";

// Drop triggers if they already exist (to avoid duplicate error)
$conn->query("DROP TRIGGER IF EXISTS increment_comment_count");
$conn->query("DROP TRIGGER IF EXISTS decrement_comment_count");

// Trigger: AFTER INSERT
$comment_after_insert_trigger = "
CREATE TRIGGER increment_comment_count
AFTER INSERT ON COMMENTS
FOR EACH ROW
BEGIN
    UPDATE blogs
    SET totalcomments = totalcomments + 1
    WHERE BLOGID = NEW.BLOGID;
END
";

// Trigger: AFTER DELETE
$comment_after_delete_trigger = "
CREATE TRIGGER decrement_comment_count
AFTER DELETE ON comments
FOR EACH ROW
BEGIN
    UPDATE blogs
    SET totalcomments = totalcomments - 1
    WHERE BLOGID = OLD.BLOGID;
END
";

// Run table creation
if ($conn->query($commentTable) === TRUE) {
    echo "Comment Table Created<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Run trigger creation
if ($conn->query($comment_after_insert_trigger) === TRUE) {
    echo "Insert Trigger Created<br>";
} else {
    echo "Error creating insert trigger: " . $conn->error . "<br>";
}

if ($conn->query($comment_after_delete_trigger) === TRUE) {
    echo "Delete Trigger Created<br>";
} else {
    echo "Error creating delete trigger: " . $conn->error . "<br>";
}


class CommentModel{

    private $conn;

    public function __construct() {
        include '../config/dbconfig.php';
        $this->conn = $conn;
    }

    public function get_comment_byblogid($blogid, $limit = 0, $offset = 0 ){
        try {
            $stmt = NULL;

            if($limit != 0){

                // WITH PAGINATION
                $stmt = $this->conn->prepare("SELECT * FROM COMMENTS blogid = ? ORDER BY CREATEDDATE DESC LIMIT ? OFFSET ?");
                $stmt->bind_param('sii', $blogid, $limit, $offset);

            }else{

                $stmt = $this->conn->prepare(
                    "SELECT * FROM COMMENTS WHERE blogid = ? ORDER BY CREATEDDATE DESC"
                );
                $stmt->bind_param('s', $blogid);
                
            }

            if($stmt->execute()){
                $result = $stmt->get_result();
                
                return $result->fetch_all(MYSQLI_ASSOC);                
            }
            else{
                
                echo "Error executing query: " . $stmt->error;
                return null;
            }

        } catch (\Throwable $th) {
            echo "Esception: ".$th->getMessage();
            return null;
        }
    }

    public function get_comments($commentid = '', $limit = 10, $offset = 0){
        try {
            
            $stmt = null;

            if($commentid == ''){

                // WITH PAGINATION
                $stmt = $this->conn->prepare("SELECT * FROM COMMENTS ORDER BY CREATEDDATE DESC LIMIT ? OFFSET ?");
                $stmt->bind_param('ii', $limit, $offset);
            }
            else{
                $stmt = $this->conn->prepare(
                    "SELECT * FROM COMMENTS WHERE COMMENTID = ?"
                );
                $stmt->bind_param('s', $commentid);
            }


            if($stmt->execute()){
                
                $result = $stmt->get_result();

                if ($blogid == '') {
                    // Return all COMMENTS as an array
                    return $result->fetch_all(MYSQLI_ASSOC);
                } else {
                    // Return single comment row
                    return $result->fetch_assoc();
                }
            }
            else{
                
                echo "Error executing query: " . $stmt->error;
                return null;
            }


        } catch (\Throwable $th) {
            echo "Exception: ".$th->getMessage();
            return null;
        }
    }

    public function insert_comment($blogid, $userid, $comment){
        try {
            
            $commentid = Generate_UUID();
            $stmt = $this->conn->prepare("INSERT INTO COMMENTS (COMMENTID, BLOGID, USERID, COMMENT)
                                VALUES (?, ?, ?, ?) ");
            $stmt->bind_param('ssss', $commentid, $blogid, $userid, $comment);
            if ($stmt->execute()) {
                echo "Comment inserted successfully!";
            } else {
                echo "Error inserting blog: " . $stmt->error;
            }

        } catch (\Throwable $th) {
            echo "Exception: ".$th->getMessage();
            return null;
        }
    }

    public function delete_comment($commentid){
        try {
            
            $stmt = $this->conn->prepare(
                "DELETE FROM COMMENTS
                WHERE COMMENTID = ?"
            );
            $stmt->bind_param("s", $commentid);

            if($stmt->execute()){

                if ($stmt->affected_rows > 0) {
                    echo "Comment deleted successfully!";
                } else {
                    echo "No comment found with ID: $commentid";
                }
            }
            else{
                echo "Error deleting comment: " . $stmt->error;
            }

        } catch (\Throwable $th) {
            echo "Exception: ".$th->getMessage();
            return null;
        }
    }
}

?>