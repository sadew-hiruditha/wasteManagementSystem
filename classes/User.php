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

    public function __construct($first_name, $last_name, $username, $password, $mobile, $street, $city, $state, $postalcode, $role = '') {
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
    
    public function updateUserInfo($con) {
    try {
        $query = "UPDATE users SET mobile = ?, street = ?, city = ?, state = ?, postalcode = ? WHERE id = ?";
        $pstmt = $con->prepare($query);
        $pstmt->bindValue(1, $this->mobile);
        $pstmt->bindValue(2, $this->street);
        $pstmt->bindValue(3, $this->city);
        $pstmt->bindValue(4, $this->state);
        $pstmt->bindValue(5, $this->postalcode);
        $pstmt->bindValue(6, $this->id);
        $pstmt->execute();
        return ($pstmt->rowCount() > 0);
    } catch (PDOException $ex) {
        die("Error in user class updateUserInfo: " . $ex->getMessage());
    }
}

public static function getTotalUsersCount($con) {
    try {
        $query = "SELECT COUNT(*) FROM users WHERE role = 'user'";
        $stmt = $con->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    } catch (PDOException $ex) {
        throw new PDOException("Error in getTotalUsersCount: " . $ex->getMessage());
    }
}

public static function getTotalDriversCount($con) {
    try {
        $query = "SELECT COUNT(*) FROM users WHERE role = 'driver'";
        $stmt = $con->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    } catch (PDOException $ex) {
        throw new PDOException("Error in getTotalDriversCount: " . $ex->getMessage());
    }
}

public static function assignDriver($con, $user_id, $driver_id) {
    try {
        $query = "UPDATE users SET assigned_driver_id = ? WHERE id = ?";
        $pstmt = $con->prepare($query);
        $pstmt->bindValue(1, $driver_id);
        $pstmt->bindValue(2, $user_id);
        $pstmt->execute();
        return ($pstmt->rowCount() > 0);
    } catch (PDOException $ex) {
        die("Error in user class assignDriver: " . $ex->getMessage());
    }
}

public static function getUserById($con, $id) {
    try {
        $query = "SELECT * FROM users WHERE id = ?";
        $pstmt = $con->prepare($query);
        $pstmt->bindValue(1, $id);
        $pstmt->execute();
        $rs = $pstmt->fetch(PDO::FETCH_OBJ);
        if (!empty($rs)) {
            $user = new User(
                $rs->firstName,
                $rs->lastName,
                $rs->username,
                '',  
                $rs->mobile,
                $rs->street,
                $rs->city,
                $rs->state,
                $rs->postalcode,
                $rs->role
            );
            $user->setId($rs->id);
            return $user;
        }
        return null;
    } catch (PDOException $exc) {
        die("Error in user class getUserById: " . $exc->getMessage());
    }
}

public static function removeDriverAssignment($con, $user_id) {
    try {
        $query = "UPDATE users SET assigned_driver_id = NULL WHERE id = ?";
        $pstmt = $con->prepare($query);
        $pstmt->bindValue(1, $user_id);
        $pstmt->execute();
        return ($pstmt->rowCount() > 0);
    } catch (PDOException $ex) {
        die("Error in user class removeDriverAssignment: " . $ex->getMessage());
    }
}
}
