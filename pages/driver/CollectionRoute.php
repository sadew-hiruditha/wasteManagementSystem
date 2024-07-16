<?php
namespace classes;

use PDO;

class CollectionRoute {
    public static function getRoutesByDriverId($conn, $driverId) {
        $query = "SELECT * FROM collection_routes WHERE driver_id = ? ORDER BY date ASC";
        $stmt = $conn->prepare($query);
        $stmt->execute([$driverId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateStatus($conn, $routeId, $status) {
        $query = "UPDATE collection_routes SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$status, $routeId]);
    }
}