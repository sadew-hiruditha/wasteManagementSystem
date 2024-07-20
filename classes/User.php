<?php

namespace classes;

use PDO;
use PDOException;

class User {

    private $id;
    private $first_name;
    private $last_name;
    private $username;
    private $password;
    private $mobile;
    private $street;
    private $city;
    private $state;
    private $postalcode;
    private $role;

    public function __construct($first_name, $last_name, $username, $password, $mobile, $street, $city, $state, $postalcode, $role = 'user') {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->username = $username;
        $this->password = $password;
        $this->mobile = $mobile;
        $this->street = $street;
        $this->city = $city;
        $this->state = $state;
        $this->postalcode = $postalcode;
        $this->role = $role;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getFirst_name() {
        return $this->first_name;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getLast_name() {
        return $this->last_name;
    }

    public function getRole() {
        return $this->role;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getMobile() {
        return $this->mobile;
    }

    public function getStreet() {
        return $this->street;
    }

    public function getCity() {
        return $this->city;
    }

    public function getState() {
        return $this->state;
    }

    public function getPostalcode() {
        return $this->postalcode;
    }

    public function setPassword($password): void {
        $this->password = $password;
    }

    public function setMobile($mobile): void {
        $this->mobile = $mobile;
    }

    public function setStreet($street): void {
        $this->street = $street;
    }

    public function setCity($city): void {
        $this->city = $city;
    }

    public function setState($state): void {
        $this->state = $state;
    }

    public function setPostalcode($postalcode): void {
        $this->postalcode = $postalcode;
    }

    public function register($con) {
        try {
            $query = "INSERT INTO users(firstName, lastName, username, password, mobile, street, city, state, postalcode, role) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $pstmt = $con->prepare($query);
            $pstmt->bindValue(1, $this->first_name);
            $pstmt->bindValue(2, $this->last_name);
            $pstmt->bindValue(3, $this->username);
            $pstmt->bindValue(4, $this->password);
            $pstmt->bindValue(5, $this->mobile);
            $pstmt->bindValue(6, $this->street);
            $pstmt->bindValue(7, $this->city);
            $pstmt->bindValue(8, $this->state);
            $pstmt->bindValue(9, $this->postalcode);
            $pstmt->bindValue(10, $this->role);
            $pstmt->execute();
            return ($pstmt->rowCount() > 0);
        } catch (PDOException $ex) {
            die("Error in user class register: " . $ex->getMessage());
        }
    }

    public function authenticate($con) {
        try {
            $query = "SELECT * FROM users WHERE username = ?";
            $pstmt = $con->prepare($query);
            $pstmt->bindValue(1, $this->username);
            $pstmt->execute();
            $rs = $pstmt->fetch(PDO::FETCH_OBJ);
            if (!empty($rs)) {
                $db_password = $rs->password;
                if (password_verify($this->password, $db_password)) {
                    $this->id = $rs->id;
                    $this->first_name = $rs->firstName;
                    $this->last_name = $rs->lastName;
                    $this->username = $rs->username; // This is the email
                    $this->mobile = $rs->mobile;
                    $this->street = $rs->street;
                    $this->city = $rs->city;
                    $this->state = $rs->state;
                    $this->postalcode = $rs->postalcode;
                    $this->role = $rs->role;
                    $this->password = null;
                    return true;
                }
            }
            return false;
        } catch (PDOException $exc) {
            die("Error in user class authenticate: " . $exc->getMessage());
        }
    }
}
