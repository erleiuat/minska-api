<?php

class User {

    private $conn;
    private $table_user = "users";
    private $table_aim = "useraims";
    private $table_detail = "userdetails";

    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;

    public function __construct($db){
        $this->conn = $db;
    }

    function create(){

        $query = "
            INSERT INTO " . $this->table_user . " SET
            Firstname = :firstname,
            Lastname = :lastname,
            Email = :email,
            Password = :password";

        $stmt = $this->conn->prepare($query);

        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);

        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        if($stmt->execute()){

            return true;

        }

        return false;

    }

    function emailExists(){

        $query = "
            SELECT ID, Firstname, Lastname, Password
            FROM " . $this->table_user . "
            WHERE Email = ?
            LIMIT 0,1";

        $stmt = $this->conn->prepare( $query );

        $this->email=htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(1, $this->email);

        $stmt->execute();

        $num = $stmt->rowCount();

        if($num>0){

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['ID'];
            $this->firstname = $row['Firstname'];
            $this->lastname = $row['Lastname'];
            $this->password = $row['Password'];

            return true;

        }

        return false;

    }

    public function update(){

        $password_set=!empty($this->password) ? ", Password = :password" : "";

        $query = "
            UPDATE " . $this->table_users . " SET
            Firstname = :firstname,
            Lastname = :lastname,
            Email = :email
            {$password_set}
            WHERE ID = :id";

        $stmt = $this->conn->prepare($query);

        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);

        if(!empty($this->password)){
            $this->password=htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }

        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()){
            return true;
        }

        return false;

    }

}
?>
