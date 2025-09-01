<?php
interface DatabaseAdapter{

    // connect to db
    public function connect();

    // get the databaseName
    public function getDatabase();

    public function insertData($table, $data);
}
?>