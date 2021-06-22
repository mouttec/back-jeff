<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");

class Database {
    private $host = "localhost";
    private $dbname = "mouttec";
    private $username = "root";
    private $password = "root";
    private $conn;

    // private $host = "localhost";
    // private $dbname = "id16881200_mouttec";
    // private $username = "id16881200_mouttecmillan";
    // private $password = "T4d9#=!6>JfBLTpZ";
    // private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8", $this->username, $this->password, $options);
        } catch (PDOException $error) {
            echo "Connection error : $error->getMessage()";
        }
        return $this->conn;
    }
}