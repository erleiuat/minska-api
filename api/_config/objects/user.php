<?php

//error_reporting(0);

class User {

    private $conn;
    private $db_table = "user";
    private $db_confirm = "user_email_confirm";
    private $db_token_view = "view_usertoken";
    private $db_confirm_view = "view_mailconfirm";

    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $email_key;
    public $language;
    public $password;
    public $gender;
    public $height;
    public $birthdate;
    public $aims;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {

        $query1 = "
        INSERT INTO " . $this->db_table . "
        (`Firstname`, `Lastname`, `Email`, `Email_Confirmed`, `Lang`, `Password`) VALUES
        (:firstname, :lastname, :email, false, :language, :password);";

        $query2 = "
        INSERT INTO " . $this->db_confirm . "
        (`User_ID`, `Confirm_Code`) VALUES
        (:id, :code);
        ";

        if ($this->emailExists()) {
            throw new Exception('email_in_use');
        }

        if (strlen($this->password)<8 && !preg_match("#[0-9]+#", $this->password) && !preg_match("#[a-zA-Z]+#", $this->password)) {
            throw new Exception('password_invalid');
        }

        $stmt = $this->conn->prepare($query1);
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':language', $this->language);
        $stmt->bindParam(':password', $password_hash);

        if ($stmt->execute()) {

            $token = '';
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            for ($i = 0; $i < 20; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $token .= $characters[$index];
            }

            $this->id = $this->conn->lastInsertId();
            $token_hash = password_hash($token, PASSWORD_BCRYPT);
            $stmt = $this->conn->prepare($query2);
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':code', $token_hash);

            if ($stmt->execute()) {
                return $token;
            } else {
                throw new Exception('token_generation_error');
            }

        }

        throw new Exception('user_creation_error');

    }

    public function emailExists() {

        $query = "SELECT ID FROM " . $this->db_table . " WHERE Email = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount()>0) {
            return true;
        } else {
            return false;
        }

    }

    public function getPassword() {

        $query = "SELECT Password FROM ".$this->db_table." WHERE Email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount()===1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['Password'];
        } else {
            throw new Exception('email_not_found');
        }

    }

    public function userToken(){

        $query = "SELECT * FROM ".$this->db_token_view." WHERE Email = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount()===1) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->aims = new stdClass();

            if($row['Email_Confirmed']){

                $this->id = $row['ID'];
                $this->firstname = $row['Firstname'];
                $this->lastname = $row['Lastname'];
                $this->language = $row['Language'];
                $this->email = $row['Email'];

                $this->gender = $row['Gender'];
                $this->height = $row['Height'];
                $this->birthdate = $row['Birthdate'];

                $this->aims->weight = $row['Aim_Weight'];
                $this->aims->date = $row['Aim_Date'];

                return true;

            } else {
                throw new Exception('email_not_confirmed');
            }

        } else {
            throw new Exception('email_not_found');
        }

    }

    public function getConfirmCode(){

        $getKey = $query = "SELECT * FROM ".$this->db_confirm_view." WHERE Email = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount()===1) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$row['Confirmed']){

                $stamp_insert = date_timestamp_get(date_create($row['Inserted']));
                $stamp_now = date_timestamp_get(date_create());
                if(($stamp_now-$stamp_insert)<= 36000){
                    return $row['Code'];
                }

                throw new Exception('code_expired');

            } else {
                throw new Exception('already_confirmed');
            }

        } else {
            throw new Exception('email_not_found');
        }


    }

    public function confirmAccount(){

        $query1 = "SELECT ID FROM user WHERE Email = ?";
        $query2 = "UPDATE user_email_confirm SET Stamp_Confirmed = CURRENT_TIMESTAMP WHERE User_ID = ?";
        $query3 = "UPDATE user SET Email_Confirmed = '1' WHERE ID = ?";

        $stmt = $this->conn->prepare($query1);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount()===1) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['ID'];

            $stmt = $this->conn->prepare($query2);
            $stmt->bindParam(1, $this->id);
            if($stmt->execute()){

                $stmt = $this->conn->prepare($query3);
                $stmt->bindParam(1, $this->id);
                if($stmt->execute()){
                    return true;
                }

            }

        }

        throw new Exception('confirmation_error');

    }

    public function update() {

        $query = "
        UPDATE " . $this->db_table . " SET
        Firstname = :firstname,
        Lastname = :lastname,
        Language = :language,
        IsFemale = :isFemale,
        Birthdate = :birthdate,
        Height = :height,
        Aim_Weight = :aim_weight,
        Aim_Date = :aim_date
        WHERE ID = :id
        ";

        $stmt = $this->conn->prepare($query);
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->language = htmlspecialchars(strip_tags($this->language));
        $this->isFemale = htmlspecialchars(strip_tags($this->isFemale));
        $this->birthdate = htmlspecialchars(strip_tags($this->birthdate));
        $this->height = htmlspecialchars(strip_tags($this->height));
        $this->aims->weight = htmlspecialchars(strip_tags($this->aims->weight));
        $this->aims->date = htmlspecialchars(strip_tags($this->aims->date));

        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':language', $this->language);
        $stmt->bindParam(':isFemale', $this->isFemale);
        $stmt->bindParam(':height', $this->height);
        $stmt->bindParam(':birthdate', $this->birthdate);
        $stmt->bindParam(':aim_weight', $this->aims->weight);
        $stmt->bindParam(':aim_date', $this->aims->date);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;

    }

}
