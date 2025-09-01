<?php

require_once __DIR__."/../helper/uuid_generator.php";



class User{

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;

        $userTable = "CREATE TABLE IF NOT EXISTS users (
            id CHAR(36) PRIMARY KEY,
            username CHAR(50) NOT NULL,
            email VARCHAR(30) UNIQUE NOT NULL, 
            phone_number VARCHAR(15) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            image VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        $this->conn->query($userTable);
    }

    public function get_allusers(){
        $sql = "SELECT * FROM users";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function insert_user($username, $email, $phone_number, $pass, $image = '') {
        $id = Generate_UUID();
        $hashpassword = password_hash($pass, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (id, username, email, phone_number, password, image) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssssss', $id, $username, $email, $phone_number, $hashpassword, $image);

        return $stmt->execute() ? "User created successfully!" : "Error creating user: " . $stmt->error;
    }

    public function get_user($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update_user($id, $username, $phone_number, $image = null) {
        if($image) {
            $sql = "UPDATE users SET username = ?, phone_number = ?, image = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ssss', $username, $phone_number, $image, $id);
        } else {
            $sql = "UPDATE users SET username = ?, phone_number = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sss', $username, $phone_number, $id);
        }
        return $stmt->execute() ? "User updated successfully!" : "Error updating user: " . $stmt->error;
    }

    public function login_user($identifier, $password) {
        $sql = "SELECT * FROM users WHERE email = ? OR username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        print_r($user);

        echo password_hash($password, PASSWORD_BCRYPT);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }


    

}
 

?>