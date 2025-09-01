<?php
require_once __DIR__.'/DatbaseAdapter.php';

class MongodbDatabase implements DatabaseAdapter{

    private $dbHost;
    private $dbPort;
    private $dbName;
    private $connectionObj;
    // private $connectionString;

    public function __construct($dbHost, $dbPort, $dbName) {
        $this->dbHost = $dbHost;
        $this->dbPort = $dbPort;
        $this->dbName = $dbName;
    }

    public function connect(){
        try {

            // Connect using host & port
            $uri = "mongodb://{$this->dbHost}:{$this->dbPort}";
            $this->connectionObj = new MongoDB\Client($uri);

            return true ?? $this->connectionObj;
        } catch (\Throwable $th) {
            echo "MongoDB Connection Exception: ".$th->getMessage();
            return null;
        }
    }

    // Get / create database
    public function getDatabase() {
        try {
            if (!$this->connectionObj) {
                $this->connect();
            }
            return $this->connectionObj->selectDatabase($this->dbName);
        } catch (\Throwable $th) {
            echo "MongoDB Database Exception: " . $th->getMessage();
            return null;
        }
    }

    // Insert data into a collection
    public function insertData($collection, $data) {
        try {
            $db = $this->getDatabase();
            $result = $db->$collection->insertOne($data);

            // echo "Inserted with ID: " . $result->getInsertedId() . "<br>";
            return $result->getInsertedId();

        } catch (\Throwable $th) {
            echo "MongoDB Insert Exception: " . $th->getMessage();
            return null;
        }
    }

}
?>