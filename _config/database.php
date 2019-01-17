<?php

    class Database {

        // Database Params
        private $host = "localhost";
        private $db_name = "minska";
        private $username = "root";
        private $password = "";

        public $conn;
        public function connect(){

            $this->conn = null;

            try {

                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);

            } catch(PDOException $exception) {

                echo "Connection error: " . $exception->getMessage();

            }

            return $this->conn;

        }

    }

?>
