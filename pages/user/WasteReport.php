<?php
namespace classes;

use PDO;

class WasteReport {
    private $userId;
    private $wasteType;
    private $amount;

    public function __construct($userId, $wasteType, $amount) {
        $this->userId = $userId;
        $this->wasteType = $wasteType;
        $this->amount = $amount;
    }

    public function save($conn) {
    try {
        // First, check if the user exists
        $checkUser = "SELECT id FROM users WHERE id = ?";
        $stmt = $conn->prepare($checkUser);
        $stmt->execute([$this->userId]);
        if ($stmt->rowCount() == 0) {
            throw new \Exception("User with ID {$this->userId} does not exist.");
        }

        // If the user exists, proceed with inserting the waste report
        $query = "INSERT INTO waste_reports (user_id, waste_type, amount) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $result = $stmt->execute([$this->userId, $this->wasteType, $this->amount]);
        
        if (!$result) {
            throw new \Exception("Failed to insert waste report.");
        }
        
        return true;
    } catch (\PDOException $e) {
        // Log the error
        error_log("Database Error: " . $e->getMessage());
        // You might want to throw the exception again or handle it as appropriate for your application
        throw $e;
    } catch (\Exception $e) {
        // Log the error
        error_log("Error: " . $e->getMessage());
        // You might want to throw the exception again or handle it as appropriate for your application
        throw $e;
    }
}

    public static function getReportsByUserId($conn, $userId) {
        $query = "SELECT * FROM waste_reports WHERE user_id = ? ORDER BY date DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}