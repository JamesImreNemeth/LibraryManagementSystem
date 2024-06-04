<?php
class Database{
    private $host = "localhost";
    private $dbName = "library_db";
    private $dbUsername = "root";
    private $dbPassword = "";
    public $connection;

    public function __construct(){
        $this->getConnection();
    }

    /**
     * Connect to the database
     */
    public function getConnection(){
        $this->connection = null;

        try{
            $this->connection = new PDO("mysql:host=".$this->host.";dbname=".$this->dbName, $this->dbUsername, $this->dbPassword);
            // Set the PDO error mode to exception
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $ex){
            echo "Connection Error: ".$ex->getMessage();
        }

        return $this->connection;
    }
}

// Usage:
$database = new Database();
$conn = $database->getConnection();
