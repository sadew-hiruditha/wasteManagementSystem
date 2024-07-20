<?php
session_start();
require_once '../../classes/DbConnector.php';
require_once '../../classes/WasteRequest.php';

use classes\DbConnector;
use classes\WasteRequest;

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}

if (isset($_GET['id'])) {
    $request_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $dbConnector = new DbConnector();
    $con = $dbConnector->getConnection();

    try {
        $request = WasteRequest::getRequestById($con, $request_id, $user_id);
        
        if ($request) {
            // Return the request details as JSON
            header('Content-Type: application/json');
            echo json_encode($request);
        } else {
            // If no request found, return an error
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Request not found']);
        }
    } catch (PDOException $ex) {
        // If there's an error, return it as JSON
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => $ex->getMessage()]);
    }
} else {
    // If no ID provided, return an error
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'No request ID provided']);
}