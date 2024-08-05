<?php

session_start();
require_once '../../classes/DbConnector.php';
require_once '../../classes/User.php';
require_once '../../classes/WasteRequest.php';

use classes\DbConnector;
use classes\User;
use classes\WasteRequest;

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    header('Location: ../../index.php');
    exit();
}

$dbConnector = new DbConnector();
$con = $dbConnector->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $request_id = $_POST['request_id'] ?? '';
    $user_id = $_SESSION['user_id'];

    try {
        switch ($action) {
            case 'edit':
                $waste_type = $_POST['waste_type'];
                $quantity = $_POST['quantity'];
                $preferred_date = $_POST['preferred_date'];
                $notes = $_POST['notes'];

                if (WasteRequest::updateRequest($con, $request_id, $user_id, $waste_type, $quantity, $preferred_date, $notes)) {
                    $_SESSION['message'] = "Request updated successfully!";
                } else {
                    $_SESSION['message'] = "Error: Failed to update request.";
                }
                break;

            case 'delete':
                if (WasteRequest::deleteRequest($con, $request_id, $user_id)) {
                    $_SESSION['message'] = "Request deleted successfully!";
                } else {
                    $_SESSION['message'] = "Error: Failed to delete request.";
                }
                break;
            case 'remove_all_completed':
                try {
                    $stmt = $con->prepare("DELETE FROM waste_requests WHERE user_id = :user_id AND status = 'Completed'");
                    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                    $stmt->execute();

                    $_SESSION['message'] = "All completed requests have been removed successfully.";
                } catch (PDOException $ex) {
                    $_SESSION['message'] = "Error: " . $ex->getMessage();
                }
                break;

            default:
                $_SESSION['message'] = "Invalid action.";
        }
    } catch (PDOException $ex) {
        $_SESSION['message'] = "Error: " . $ex->getMessage();
    }

    header("Location: view_requests.php");
    exit();
}