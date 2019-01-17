<?php
//error_reporting(0);
class Template {

    private $conn;
    private $db_table = "templates";

    public $id;
    public $userid;
    public $title;
    public $calories;
    public $amount;
    public $image;

    public function __construct($db){
        $this->conn = $db;
    }

    function read($amount = false, $order = 'DESC'){

        $query = "
        SELECT ID as id, Weight as weight, MeasureDate as measuredate, CreationDate as creationdate
        FROM ". $this->db_table . "
        WHERE UserID = :userid
        ORDER BY CreationDate ". $order;

        if($amount){
            $query .= " LIMIT ". $amount;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userid', $this->userid);
        $stmt->execute();

        return $stmt;

    }

    function create(){

        $query = "
            INSERT INTO " . $this->db_table . " SET
            UserID = :userid,
            Title = :title,
            DefaultAmout = :amount,
            Calories = :calories,
            Image = :image
        ";

        $this->userid=htmlspecialchars(strip_tags($this->userid));
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->calories=htmlspecialchars(strip_tags($this->calories));
        $this->amount=htmlspecialchars(strip_tags($this->amount));
        $this->image=htmlspecialchars(strip_tags($this->image));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userid", $this->userid);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":calories", $this->calories);
        $stmt->bindParam(":image", $this->image);

        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;

    }

    function delete(){

        $query = "
        DELETE FROM " . $this->db_table . "
        WHERE ID = :id AND UserID = :userid
        ";

        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->userid=htmlspecialchars(strip_tags($this->userid));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":userid", $this->userid);

        if($stmt->execute()){

            return true;

        }

        return false;

    }

}
?>
