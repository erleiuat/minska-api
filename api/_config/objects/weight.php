<?php
//error_reporting(0);
class Weight {

    private $conn;
    private $db_table = "weights";

    public $id;
    public $userid;
    public $weight;
    public $measuredate;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($amount = false, $order = 'DESC') {

        $query = "
        SELECT ID as id, Weight as weight, MeasureDate as measuredate, CreationDate as creationdate
        FROM ". $this->db_table . "
        WHERE UserID = :userid
        ORDER BY CreationDate ". $order;

        if ($amount) {
            $query .= " LIMIT " . $amount;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userid', $this->userid);
        $stmt->execute();

        return $stmt;

    }

    public function create() {

        $query = "
            INSERT INTO " . $this->db_table . " SET
            UserID = :userid,
            Weight = :weight,
            MeasureDate = :measuredate
        ";

        $this->userid = htmlspecialchars(strip_tags($this->userid));
        $this->weight = htmlspecialchars(strip_tags($this->weight));
        $this->measuredate = htmlspecialchars(strip_tags($this->measuredate));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userid", $this->userid);
        $stmt->bindParam(":weight", $this->weight);
        $stmt->bindParam(":measuredate", $this->measuredate);


        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;

    }

    public function delete() {

        $query = "
        DELETE FROM " . $this->db_table . "
        WHERE ID = :id AND UserID = :userid
        ";

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->userid = htmlspecialchars(strip_tags($this->userid));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":userid", $this->userid);


        if ($stmt->execute()) {

            return true;

        }

        return false;

    }

}
