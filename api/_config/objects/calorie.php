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
        SELECT ID as id, Title as title, Calories as calories, Amount as amount
        FROM ". $this->db_table . "
        WHERE UserID = :userid
        AND Date = :date
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userid', $this->userid);
        $stmt->bindParam(':date', $this->date);
        $stmt->execute();

        return $stmt;

    }

    public function readDays($order = 'DESC') {

        $query = "
        SELECT Date as date FROM ". $this->db_table . "
        WHERE UserID = :userid
        GROUP BY Date
        ORDER BY Date ".$order;

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userid', $this->userid);
        $stmt->execute();

        return $stmt;

    }

    public function create() {

        $query = "
        INSERT INTO " . $this->db_table . " SET
        UserID = :userid,
        Title = :title,
        Calories = :calories,
        Amount = :amount,
        Date = :date
        ";

        $this->userid = htmlspecialchars(strip_tags($this->userid));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->calories = htmlspecialchars(strip_tags($this->calories));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->date = htmlspecialchars(strip_tags($this->date));

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
