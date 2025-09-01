<?php
require_once __DIR__.'/DatbaseAdapter.php';

class MySqlDatabase implements DatabaseAdapter{

    private $dbHost;
    private $dbUsername;
    private $dbPassword;
    private $dbName;
    private $connectionObj;

    public function __construct($dbHost, $dbUsername, $dbPassword, $dbName) {
        $this->dbHost = $dbHost;
        $this->dbUsername = $dbUsername;
        $this->dbPassword = $dbPassword;
        $this->dbName = $dbName;
    }

    public function connect(){
        try {
            $this->connectionObj = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword);

            if($this->connectionObj->connect_error){
                die("Connection Failed :".$this->connectionObj->connect_error);
                return false;
            }
            // echo "Connected to MySQL server successfully.<br>";
            return true ?? $this->connectionObj;

        } catch (\Throwable $th) {
            echo "Exception: ".$th->getMessage();
            return null;
        }
    }

    public function getDatabase(){
        try {

            // Ensure we have an active connection
            if (!$this->connectionObj || $this->connectionObj->connect_error) {
                $this->connect();
            }

            $sql="CREATE DATABASE if not exists {$this->dbName}";

            if($this->connectionObj->query($sql)==TRUE){
                echo "Database '{$this->dbName}' created Successfully.<br>";
                $this->connectionObj->select_db($this->dbName);
                return $this->connectionObj;
            }
            else{
                echo "Error Creating Database :".$this->connectionObj->error;
                return null;
            }

        } catch (\Throwable $th) {
            echo "Exception: ".$th->getMessage();
            return null;
        }
    }

    public function insertData($table, $data){
        try {

            // Ensure we have a connection & database selected
            if (!$this->connectionObj || $this->connectionObj->connect_error) {
                $this->connect();
                $this->connectionObj->select_db($this->dbName);
            }
            
            $columns = implode(", ", array_keys($data));
            $values  = implode("', '", array_values($data));
            $sql = "INSERT INTO $table ($columns) VALUES ('$values')";
            
            if ($this->connectionObj->query($sql) === TRUE) {
                echo "Record inserted successfully.<br>";
                return true;
            } else {
                echo "Error: " . $this->connectionObj->error;
                return false;
            }
            
        } catch (\Throwable $th) {
            echo "Exception: ".$th->getMessage();
            return false;
        }
    }
}
?>