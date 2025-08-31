<?php


$host='localhost';
$user="root";
$pass ="";
$dbname="Travel_Blogging_Platform";

$conn=new mysqli($host,$user,$pass);


if($conn->connect_error){
    die("Connection Failed :".$conn->connect_error);


}



$sql="create database if not exists $dbname";

if($conn->query($sql)==TRUE){
    //echo "Database '$dbname' created Successfully";

 }
else{
   echo "Error Creating Database :".$conn->error;

}

$conn->select_db($dbname)




?>