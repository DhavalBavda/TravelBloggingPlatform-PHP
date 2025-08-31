<?php

include '../helper/uuid_generator.php';



class UserModel{

    private $conn;
    
   


    public function __construct($conn) {
        

        $this->conn = $conn;
                    

        $userTable="create table if not exists users(
            id char(36) primary key,
            username char(50) not null,
            email varchar(30) unique not null, 
            phone_number varchar(15) not null unique,
            password varchar(30) not null ,
            created_at timestamp default current_timestamp
            )";


            $conn->query($userTable);

            if ($conn->query($userTable)==True){
               // echo "<br/> Kya Baat Hai Bhai Tu To Developer Nikala , Table Bana Diya Bhai , Pretty Impresive, Enjoy The User Table Bro. <br/>";

            }

            else {
                //echo " Error Bro Dekh Tune Kya Kiya , Coding Nahi AAti To Mat Kiya Karna ".$conn->error;

            }
        }

    public function insert_user($username,$email,$phone_number,$pass){
        $id=Generate_UUID();
        $hashpassword=password_hash($pass,PASSWORD_BCRYPT);

        $sql="insert into users (id, username, email, phone_number, password) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt=$this->conn->prepare($sql);

        $stmt->bind_param('sssss',$id,$username,$email, $phone_number, $hashpassword);

        if($stmt->execute()){
            return "Bhai User Create Ho Gaya Enjoy Kar !! <br/>";

        }
        else{
            return "Bhai Tu Ek Simple USer Create Nahi Kar Sakta, Kya Coder Banega Re Tu , Dekh Kya Kiya :- ".$stmt->error."<br/>";
        }
    }
    
    public function get_user($id){
        $sql = "select * From users where id= ?";

        $stmt=$this->conn->prepare($sql);
        $stmt->bind_param('s',$id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();

    }


    public function update_user($id,$username,$phone_number){
        $sql='update users set username=? , phone_number=? where id = ?';
        $stmt=$this->conn->prepare($sql);
        $stmt->bind_param('sss',$username,$phone_number,$id);

        return $stmt->execute() ? "<br/> Bhai User Converted Kar Diya <br/>":"Bhai  Tu Nahi KAr Sakta Kyu ki :-".$stmt->error."<br/>";


        
        
    }


    

}

///// Tesing For  Files 


// $user = new UserModel();


// // 1. user Create 
// // echo $user->insert_user("Anu", "anu@example.com", "987652109788", "mypassword");

// // 2. Read User (replace with actual UUID from DB after insert)
// $someId = "ad0da06e-c8cc-441a-a213-475e527e754e"; 
// // $data = $user->get_user($someId);
// // if ($data) {
// //     echo "<br/>  User Found: " . $data['username'] . " (" . $data['email'] . ")<br>";
// // } else {
// //     echo " User not found<br>";
// // }

// // 3. Update User
// echo $user->update_user($someId, "Anu Updated", "999999999");


?>