<?php
session_start();
require_once '../../classes/DbConnector.php';
require_once '../../classes/User.php';
require_once '../../classes/WasteRequest.php';

use classes\DbConnector;
use classes\User;
use classes\WasteRequest;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dbConnector = new DbConnector();
    $con = $dbConnector->getConnection();

    $user_id = $_SESSION['user_id'];
    $waste_type = $_POST['waste_type'];
    $quantity = $_POST['quantity'];
    $preferred_date = $_POST['preferred_date'];
    $notes = $_POST['notes'];

    $wasteRequest = new WasteRequest($user_id, $waste_type, $quantity, $preferred_date, $notes);

    try {
        if ($wasteRequest->createRequest($con)) {
            $_SESSION['message'] = "Request submitted successfully!";
        } else {
            $_SESSION['message'] = "Error: Failed to submit request.";
        }
    } catch (PDOException $ex) {
        $_SESSION['message'] = "Error: " . $ex->getMessage();
    }

    header("Location: request_collection.php");
    exit();
}