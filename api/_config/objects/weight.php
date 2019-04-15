<?php
//error_reporting(0);
class Weight {

    private $conn;
    private $db_table = "weight";

    public $id;
    public $userid;
    public $weight;
    public $measuredate;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($amount = false, $order = 'DESC') {

        $query = "
        SELECT ID as id, Weight as weight, Date_Weighed as measuredate, Stamp_Insert as creationdate
        FROM ". $this->db_table . "
        WHERE User_ID = :userid
        ORDER BY Stamp_Insert ". $order;

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
            User_ID = :userid,
            Weight = :weight,
            Date_Weighed = :measuredate
        ";

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
        WHERE ID = :id AND User_ID = :userid
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":userid", $this->userid);

        if ($stmt->execute()) {
            return true;
        }

        return false;

    }

}
