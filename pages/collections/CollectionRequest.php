<?php
namespace classes;

use PDO;

class CollectionRequest {
    private $userId;
    private $requestDate;

    public function __construct($userId, $requestDate) {
        $this->userId = $userId;
        $this->requestDate = $requestDate;
    }

    public function save($conn) {
        $query = "INSERT INTO collection_requests (user_id, request_date, status) VALUES (?, ?, 'Pending')";
        $stmt = $conn->prepare($query);
        $stmt->execute([$this->userId, $this->requestDate]);
    }

    public static function getRequestsByUserId($conn, $userId) {
        $query = "SELECT * FROM collection_requests WHERE user_id = ? ORDER BY request_date DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}