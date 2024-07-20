<?php
session_start();
require_once '../../classes/DbConnector.php';
require_once '../../classes/Driver.php';

use classes\DbConnector;
use classes\Driver;

if (!isset($_SESSION['driver_id']) || !isset($_GET['id']) || !isset($_GET['action'])) {
    header('Location: driverDashboard.php');
    exit();
}

$dbConnector = new DbConnector();
$con = $dbConnector->getConnection();

$driver = new Driver('', '', '', '');
$driver->setId($_SESSION['driver_id']);

$pickup_id = $_GET['id'];
$action = $_GET['action'];

$status = ($action === 'complete') ? 'Completed' : 'Cancelled';

if ($driver->updatePickupStatus($con, $pickup_id, $status)) {
    $_SESSION['message'] = "Pickup status updated successfully.";
} else {
    $_SESSION['message'] = "Failed to update pickup status.";
}

header('Location: driverDashboard.php');
exit();