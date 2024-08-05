<?php

namespace classes;

use PDO;
use PDOException;

class WasteRequest {

    private $id;
    private $user_id;
    private $waste_type;
    private $quantity;
    private $preferred_date;
    private $notes;

    public function __construct($user_id, $waste_type, $quantity, $preferred_date, $notes) {
        $this->user_id = $user_id;
        $this->waste_type = $waste_type;
        $this->quantity = $quantity;
        $this->preferred_date = $preferred_date;
        $this->notes = $notes;
    }

    public function getId() {
        return $this->id;
    }

    public function getUser_id() {
        return $this->user_id;
    }

    public function getWaste_type() {
        return $this->waste_type;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getPreferred_date() {
        return $this->preferred_date;
    }

    public function getNotes() {
        return $this->notes;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setUser_id($user_id): void {
        $this->user_id = $user_id;
    }

    public function setWaste_type($waste_type): void {
        $this->waste_type = $waste_type;
    }

    public function setQuantity($quantity): void {
        $this->quantity = $quantity;
    }

    public function setPreferred_date($preferred_date): void {
        $this->preferred_date = $preferred_date;
    }

    public function setNotes($notes): void {
        $this->notes = $notes;
    }

    public function createRequest($con) {
        try {
            $query = "INSERT INTO waste_requests (user_id, waste_type, quantity, preferred_date, notes) 
                      VALUES (?, ?, ?, ?, ?)";
            $pstmt = $con->prepare($query);
            $pstmt->bindValue(1, $this->user_id);
            $pstmt->bindValue(2, $this->waste_type);
            $pstmt->bindValue(3, $this->quantity);
            $pstmt->bindValue(4, $this->preferred_date);
            $pstmt->bindValue(5, $this->notes);
            $pstmt->execute();
            return ($pstmt->rowCount() > 0);
        } catch (PDOException $ex) {
            throw new PDOException("Error in WasteRequest class createRequest: " . $ex->getMessage());
        }
    }

    public static function getRequests($con, $user_id) {
        try {
            $query = "SELECT * FROM waste_requests WHERE user_id = ? ORDER BY preferred_date DESC";
            $stmt = $con->prepare($query);
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            throw new PDOException("Error in getRequests: " . $ex->getMessage());
        }
    }

    public static function getRequestById($con, $id, $user_id) {
        try {
            $query = "SELECT * FROM waste_requests WHERE id = ? AND user_id = ?";
            $stmt = $con->prepare($query);
            $stmt->execute([$id, $user_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            throw new PDOException("Error in getRequestById: " . $ex->getMessage());
        }
    }

    public static function updateRequest($con, $id, $user_id, $waste_type, $quantity, $preferred_date, $notes) {
        try {
            $query = "UPDATE waste_requests SET waste_type = ?, quantity = ?, preferred_date = ?, notes = ? 
                      WHERE id = ? AND user_id = ?";
            $stmt = $con->prepare($query);
            $stmt->execute([$waste_type, $quantity, $preferred_date, $notes, $id, $user_id]);
            return ($stmt->rowCount() > 0);
        } catch (PDOException $ex) {
            throw new PDOException("Error in updateRequest: " . $ex->getMessage());
        }
    }

    public static function deleteRequest($con, $id, $user_id) {
        try {
            $query = "DELETE FROM waste_requests WHERE id = ? AND user_id = ?";
            $stmt = $con->prepare($query);
            $stmt->execute([$id, $user_id]);
            return ($stmt->rowCount() > 0);
        } catch (PDOException $ex) {
            throw new PDOException("Error in deleteRequest: " . $ex->getMessage());
        }
    }

   
    
    public static function getAllRequests($conn) {
    $query = "SELECT cr.id, cr.user_id, CONCAT(u.firstname, ' ', u.lastname) AS customer_name, 
                     cr.waste_type, cr.quantity, cr.preferred_date, cr.status
              FROM waste_requests cr
              JOIN users u ON cr.user_id = u.id
              ORDER BY cr.user_id ASC, cr.preferred_date ASC";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}
    

    public static function updateRequestStatus($conn, $requestId, $status) {
        // Ensure $status is one of the allowed enum values
        if (!in_array($status, ['Pending', 'Approved', 'Completed'])) {
            throw new \InvalidArgumentException("Invalid status");
        }

        $query = "UPDATE waste_requests SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        return $stmt->execute([$status, $requestId]);
    }

    public static function getRequestCountByStatus($con, $user_id, $status) {
        try {
            $query = "SELECT COUNT(*) FROM waste_requests WHERE user_id = ? AND status = ?";
            $stmt = $con->prepare($query);
            $stmt->execute([$user_id, $status]);
            return $stmt->fetchColumn();
        } catch (PDOException $ex) {
            throw new PDOException("Error in getRequestCountByStatus: " . $ex->getMessage());
        }
    }

    public static function getPendingRequestCount($con, $user_id) {
        return self::getRequestCountByStatus($con, $user_id, 'Pending');
    }

    public static function getApprovedRequestCount($con, $user_id) {
        return self::getRequestCountByStatus($con, $user_id, 'Approved');
    }

    public static function getCompletedRequestCount($con, $user_id) {
        return self::getRequestCountByStatus($con, $user_id, 'Completed');
    }
    
    
}
