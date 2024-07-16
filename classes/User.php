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
    private $role;

    public function __construct($first_name, $last_name, $username, $password, $role) {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->username = $username;
        $this->password = $password;
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

    public function register($con) {
        try {
//        $con = DbConnector::getConnection();
            $query = "INSERT INTO users(firstName,lastName,username,password,role) VALUES (?,?,?,?,?)";
            $pstmt = $con->prepare($query);
            $pstmt->bindValue(1, $this->first_name);
            $pstmt->bindValue(2, $this->last_name);
            $pstmt->bindValue(3, $this->username);
            $pstmt->bindValue(4, $this->password);
            $pstmt->bindValue(5, $this->role);
            $pstmt->execute();

            return($pstmt->rowCount() > 0);
        } catch (PDOException $ex) {
            die("Error in user class register" . $ex->getMessage());
        }
    }

    public function authenticate($con) {
        try {
            $query = "SELECT * From users WHERE username = ?";
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
                    $this->role = $rs->role;
                    $this->password = null;

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $exc) {
            die("Error in user class authenticate" . $exc->getMessage());
        }
    }
}
