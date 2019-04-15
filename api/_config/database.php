<?php

class Database {

    private $host;
    private $db_name;
    private $username;
    private $password;

    public $conn;
    public function connect($params) {

        $this->conn = null;
        $this->host = $params["host"];
        $this->db_name = $params["database"];
        $this->username = $params["user"];
        $this->password = $params["pass"];

        try {

            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";
                dbname=" . $this->db_name, $this->username, $this->password
            );
            $this->conn->exec("SET NAMES utf8");

        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;

    }
}
