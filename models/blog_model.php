<?php

require_once __DIR__."/../helper/uuid_generator.php";

class BlogModel{
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
            echo "Blog Table Created";
        } else {
            echo " Error creating table: ".$conn->error;
        }
    }

        // GET ALL DATA WITH PAGINATION AND SINGLE BLOG DATA BY ID
    public function get_blogs($blogid = '', $limit = 10, $offset = 0){
        try {
            $stmt = NULL;

            if($blogid == ''){

                // WITH PAGINATION
                $stmt = $this->conn->prepare("SELECT * FROM BLOGS ORDER BY CREATEDDATE DESC LIMIT ? OFFSET ?");
                $stmt->bind_param('ii', $limit, $offset);
            }else{
                $stmt = $this->conn->prepare(
                    "SELECT * FROM BLOGS WHERE BLOGID = ?"
                );
                $stmt->bind_param('s', $blogid);
            }

            if($stmt->execute()){
                
                $result = $stmt->get_result();

                if ($blogid == '') {
                    // Return all blogs as an array
                    return $result->fetch_all(MYSQLI_ASSOC);
                } else {
                    // Return single blog row
                    return $result->fetch_assoc();
                }
            }
            else{
                
                echo "Error executing query: " . $stmt->error;
                return null;
            }

        } catch (\Throwable $th) {
            echo "Esception: ".$th->getMessage();
        }
    }

        // GET USER WISE DATA WITH PAGINATION AND SINGLE BLOG DATA BY ID
    public function get_blogs_byuserid($authorid, $limit = 0, $offset = 0){
        try {
            $stmt = NULL;

            if($limit != 0){

                // WITH PAGINATION
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
                // Always return an array (list of blogs for this author)
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

        // INSERT DATA
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

        // UPDATE DATA
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

        // DELETE DATA
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





// Test the blog 

// $blogM = new BlogModel();

// //  1. blog Create 
//   echo $blogM->insert_blog('9d062656-d275-4b67-950c-8185cad5f88f', "My first Blog", 'Shoryt desc', 'dfsdfsdfsdfdfs dAFSDKFNSKDF SD FMSDKFNSKDF', 'DSDF.JPEG');

// // 2. blog update 
// echo $blogM->update_blog('1897c4f7-61f5-4b7c-8a84-a0ceaa2af46a', "My update blog", "short", 'dfsdf', 'img.jpg,')

// // 3. get blogs 
// echo json_encode($blogM->get_blogs('1897c4f7-61f5-4b7c-8a84-a0ceaa2af46a'));
// echo json_encode($blogM->get_blogs('', 1, 1));

// // 4. get by user id 
// echo json_encode($blogM->get_blogs_byuserid('9d062656-d275-4b67-950c-8185cad5f88f'));

// // 5. delete blog 
// $blogM->delete_blog('1897c4f7-61f5-4b7c-8a84-a0ceaa2af46a');
?>