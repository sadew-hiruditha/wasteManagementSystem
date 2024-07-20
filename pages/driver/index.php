<?php
session_start();
require_once '../../classes/DbConnector.php';
require_once '../../classes/User.php';
require_once '../collections/CollectionRequest.php';
require_once '../collections/CollectionSchedule.php';
require_once './CollectionRoute.php';

use classes\DbConnector;
use classes\User;
use classes\CollectionRoute;
use classes\CollectionSchedule;

// Check if user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'driver') {
    header('Location: ../../index.php');
    exit();
}

$user = new User($_SESSION['user_firstname'], $_SESSION['user_lastname'], $_SESSION['user_name'], '', 'driver');
$user->setId($_SESSION['user_id']);

$conn = DbConnector::getConnection();

// Handle collection status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $routeId = $_POST['route_id'];
    $status = $_POST['status'];
    CollectionRoute::updateStatus($conn, $routeId, $status);
}

// Fetch driver's assigned routes
$assignedRoutes = CollectionRoute::getRoutesByDriverId($conn, $user->getId());

// Fetch collection schedule
$schedule = CollectionSchedule::getUpcomingSchedule($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard - Waste Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Waste Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Welcome, Driver <?php echo htmlspecialchars($user->getFirst_name()); ?>!</h1>
        
        <div class="row mt-4">
            <div class="col-12">
                <h2>Your Assigned Routes</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Area</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assignedRoutes as $route): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($route['date']); ?></td>
                                <td><?php echo htmlspecialchars($route['area']); ?></td>
                                <td><?php echo htmlspecialchars($route['status']); ?></td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="route_id" value="<?php echo $route['id']; ?>">
                                        <select name="status" class="form-select form-select-sm d-inline-block w-auto">
                                            <option value="Pending" <?php echo $route['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="In Progress" <?php echo $route['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                            <option value="Completed" <?php echo $route['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-primary btn-sm">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <h2>Upcoming Collection Schedule</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Area</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schedule as $collection): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($collection['date']); ?></td>
                                <td><?php echo htmlspecialchars($collection['area']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>