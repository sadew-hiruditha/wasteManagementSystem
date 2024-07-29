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

    // Getters and setters (unchanged)

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
}


class WasteRequests {
    public static function getAllRequests($conn) {
        $query = "SELECT cr.id, CONCAT(u.firstname, ' ', u.lastname) AS customer_name, 
                         cr.waste_type, cr.quantity, cr.preferred_date, cr.status
                  FROM waste_requests cr
                  JOIN users u ON cr.user_id = u.id
                  ORDER BY cr.preferred_date ASC";
        
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
