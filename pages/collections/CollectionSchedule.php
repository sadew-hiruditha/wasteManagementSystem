<?php
namespace classes;

use PDO;

class CollectionSchedule {
    public static function getUpcomingSchedule($conn) {
        $query = "SELECT * FROM collection_schedule WHERE date >= CURDATE() ORDER BY date ASC LIMIT 5";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}