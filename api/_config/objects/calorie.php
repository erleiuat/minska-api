<?php
//error_reporting(0);
class Calorie {

    private $conn;
    private $db_table = "calorie";

    public $id;
    public $userid;
    public $title;
    public $calories;
    public $amount;
    public $date;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readByDay() {

        $query = "
        SELECT ID as id, Title as title, Calories_per_100 as calories, Amount as amount
        FROM ". $this->db_table . "
        WHERE User_ID = :userid
        AND Stamp_Consumed = :date
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userid', $this->userid);
        $stmt->bindParam(':date', $this->date);
        $stmt->execute();

        return $stmt;

    }

    public function readDays($order = 'DESC') {

        $query = "
        SELECT Stamp_Consumed as date FROM ". $this->db_table . "
        WHERE User_ID = :userid
        GROUP BY Stamp_Consumed
        ORDER BY Stamp_Consumed ".$order;

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userid', $this->userid);
        $stmt->execute();

        return $stmt;

    }

    public function create() {

        $query = "
        INSERT INTO " . $this->db_table . " SET
        User_ID = :userid,
        Title = :title,
        Calories_per_100 = :calories,
        Amount = :amount,
        Stamp_Consumed = :date
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userid", $this->userid);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":calories", $this->calories);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":date", $this->date);

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
