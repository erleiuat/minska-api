<?php
//error_reporting(0);
class Template {

    private $conn;
    private $db_table = "template";

    public $id;
    public $userid;
    public $title;
    public $calories;
    public $amount;
    public $image;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($amount = false) {

        $query = "
        SELECT ID as id, Title as title, Default_Amount as amount, Calories_per_100 as calories, Image as image
        FROM ". $this->db_table . "
        WHERE User_ID = :userid";

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
            Title = :title,
            Calories_per_100 = :calories,
            Default_Amount = :amount,
            Image = :image
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userid", $this->userid);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":calories", $this->calories);
        $stmt->bindParam(":image", $this->image);

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
