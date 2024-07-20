<?php
namespace classes;

use PDO;
use PDOException;

class Driver {
    private $id;
    private $first_name;
    private $last_name;
    private $username;
    private $password;
    private $vehicle_id;

    public function __construct($first_name, $last_name, $username, $password, $vehicle_id = null) {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->username = $username;
        $this->password = $password;
        $this->vehicle_id = $vehicle_id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getFullName() {
        return $this->first_name . ' ' . $this->last_name;
    }

//    public function authenticate($con) {
//        try {
//            $query = "SELECT * FROM drivers WHERE username = ?";
//            $pstmt = $con->prepare($query);
//            $pstmt->bindValue(1, $this->username);
//            $pstmt->execute();
//            $rs = $pstmt->fetch(PDO::FETCH_OBJ);
//            if (!empty($rs)) {
//                $db_password = $rs->password;
//                if (password_verify($this->password, $db_password)) {
//                    $this->id = $rs->id;
//                    $this->first_name = $rs->first_name;
//                    $this->last_name = $rs->last_name;
//                    $this->vehicle_id = $rs->vehicle_id;
//                    $this->password = null;
//                    return true;
//                }
//            }
//            return false;
//        } catch (PDOException $exc) {
//            die("Error in driver class authenticate: " . $exc->getMessage());
//        }
//    }

    public function getAssignedPickups($con) {
        try {
            $query = "SELECT wr.*, u.first_name, u.last_name 
                      FROM waste_requests wr 
                      JOIN users u ON wr.user_id = u.id 
                      WHERE wr.driver_id = ? AND wr.status = 'Assigned'
                      ORDER BY wr.preferred_date ASC";
            $pstmt = $con->prepare($query);
            $pstmt->bindValue(1, $this->id);
            $pstmt->execute();
            return $pstmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $exc) {
            die("Error in driver class getAssignedPickups: " . $exc->getMessage());
        }
    }

    public function updatePickupStatus($con, $pickup_id, $status) {
        try {
            $query = "UPDATE waste_requests SET status = ? WHERE id = ? AND driver_id = ?";
            $pstmt = $con->prepare($query);
            $pstmt->bindValue(1, $status);
            $pstmt->bindValue(2, $pickup_id);
            $pstmt->bindValue(3, $this->id);
            $pstmt->execute();
            return ($pstmt->rowCount() > 0);
        } catch (PDOException $exc) {
            die("Error in driver class updatePickupStatus: " . $exc->getMessage());
        }
    }
}