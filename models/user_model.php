<?php

include '../config/dbconfig.';
include '../helper/uuid_generator.php';



$userTable="create table if not exists users(
id char(36) primary key,
username char(50) not null,
email varchar(30) unique not null, 
phone_number varchar(15) not null unique,
password varchar(30) not null ,
created_at timestamp default current_timestamp
";


$conn->query($userTable);

if ($conn->query($userTable)==True){
    echo " Kya Baat Hai Bhai Tu To Developer Nikala , Table Bana Diya Bhai , Pretty Impresive, Enjoy The User Table Bro.";

}

else {
    echo " Error Bro Dekh Tune Kya Kiya , Coding Nahi AAti To Mat Kiya Karna ".$conn->error;

}

class UserModel{

    private $conn;
    private $id;
   


    public function __construct($db) {
        $this->conn = $db;
    }

    public function insert_user($username,$email,$phone_number,$pass){
        $id=Generate_UUID();
        $hashpassword=password_hash($pass,PASSWORD_BCRYPT);

        $sql="insert into users (id, username, email, phone_number, password) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt=$this->conn->prepare($sql);

        $stmt->bind_param('sssss',$id,$username,$email, $phone_number, $hashpassword);

        if($stmt->execute()){
            return "Bhai User Create Ho Gaya Enjoy Kar !!";

        }
        else{
            return "Bhai Tu Ek Simple USer Create Nahi Kar Sakta, Kya Code Banega Re Tu , Dekh Kya Kiya :- ".$stmt->error;
        }
    }
    

    

}


?>