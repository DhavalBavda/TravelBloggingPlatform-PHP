<?php

require_once __DIR__."/../helper/uuid_generator.php";

class Blog{
    private $conn;

    public function __construct($conn) {

        $this->conn = $conn;

        $blogTable="CREATE TABLE IF NOT EXISTS blogs(
            BLOGID char(36) primary key,
            AUTHORID char(36) not null,
            TITLE varchar(255) not null,
            SHORTDESC text not null,
            CONTENT longtext,
            IMAGES varchar(255),
            CREATEDDATE datetime default current_timestamp,
            TOTALCOMMENTS int default 0,
            foreign key (AUTHORID) references users(ID) on delete cascade
        );";

        if ($conn->query($blogTable)==True){
            // echo "Blog Table Created";
        } else {
            // echo " Error creating table: ".$conn->error;
        }
    }

    public function get_blogs($blogid = '', $limit = 10, $offset = 0, $search = ''){
        try {
            $stmt = NULL;

            if ($blogid == '') {

                if (!empty($search)) {

                    $searchParam = "%" . $search . "%";

                    $stmt = $this->conn->prepare(
                        "SELECT b.*, u.username AS author_name
                        FROM BLOGS b
                        JOIN users u ON b.AUTHORID = u.id
                        WHERE b.TITLE LIKE ? 
                            OR b.SHORTDESC LIKE ?
                            OR u.username LIKE ?
                        ORDER BY b.CREATEDDATE DESC 
                        LIMIT ? OFFSET ?"
                    );
                    $stmt->bind_param('sssii', $searchParam, $searchParam, $searchParam, $limit, $offset);

                } else {

                    $stmt = $this->conn->prepare(
                        "SELECT b.*, u.username AS author_name
                        FROM BLOGS b
                        JOIN users u ON b.AUTHORID = u.id
                        ORDER BY b.CREATEDDATE DESC 
                        LIMIT ? OFFSET ?"
                    );
                    $stmt->bind_param('ii', $limit, $offset);

                }
            } else {

                $stmt = $this->conn->prepare(
                    "SELECT b.*, u.username AS author_name
                    FROM BLOGS b
                    JOIN users u ON b.AUTHORID = u.id
                    WHERE b.BLOGID = ?"
                );
                $stmt->bind_param('s', $blogid);

            }

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($blogid == '') {
                    return $result->fetch_all(MYSQLI_ASSOC);
                } else {
                    return $result->fetch_assoc();
                }
            } else {
                echo "Error executing query: " . $stmt->error;
                return null;
            }
            
        } catch (\Throwable $th) {
            echo "Exception: " . $th->getMessage();
            return null;
        }
    }

    public function get_blogs_byuserid($authorid, $limit = 0, $offset = 0){
        try {
            $stmt = NULL;

            if($limit != 0){

                $stmt = $this->conn->prepare("SELECT * FROM BLOGS authorid = ? ORDER BY CREATEDDATE DESC LIMIT ? OFFSET ?");
                $stmt->bind_param('sii', $authorid, $limit, $offset);

            }else{

                $stmt = $this->conn->prepare(
                    "SELECT * FROM BLOGS WHERE authorid = ? ORDER BY CREATEDDATE DESC"
                );
                $stmt->bind_param('s', $authorid);
                
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

    public function insert_blog($authorid, $title, $shortdesc, $content, $images = ''){
        try {

            $blogid = Generate_UUID();
            $stmt = $this->conn->prepare("INSERT INTO BLOGS (BLOGID, AUTHORID, TITLE, SHORTDESC, CONTENT, IMAGES)
                                    VALUES (?, ?, ?, ?, ?, ?) ");
            $stmt->bind_param("ssssss", $blogid, $authorid, $title, $shortdesc, $content, $images);
            if ($stmt->execute()) {
                echo "Blog inserted successfully!";
            } else {
                echo "Error inserting blog: " . $stmt->error;
            }

        } catch (\Throwable $th) {
            echo "Exception: " . $th->getMessage();
            return null;
        }
    }

    public function update_blog($blogid, $title, $shortdesc, $content, $images ){
        try {
            $stmt = $this->conn->prepare(
                "UPDATE BLOGS
                SET TITLE = ?, SHORTDESC = ?, CONTENT = ?, IMAGES = ?
                WHERE BLOGID = ?"
            );
            $stmt->bind_param("sssss", $title, $shortdesc, $content, $images, $blogid);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "Blog updated successfully!";
                } else {
                    echo "No blog found with ID: " . $blogid;
                }
            } else {
                echo "Error updating blog: " . $stmt->error;
            }

        } catch (\Throwable $th) {
            echo "Exception: " . $th->getMessage();
        }
    }

    public function delete_blog($blogid){
        try {
            
            $stmt = $this->conn->prepare(
                "DELETE FROM BLOGS
                WHERE BLOGID = ?"
            );
            $stmt->bind_param("s", $blogid);

            if($stmt->execute()){
                if ($stmt->affected_rows > 0) {
                    echo "Blog deleted successfully!";
                } else {
                    echo "No blog found with ID: $blogid";
                }
            }
            else{
                echo "Error deleting blog: " . $stmt->error;
            }

        } catch (\Throwable $th) {
            echo "Exception: ". $th->getMessage();
        }
    }

}





?>