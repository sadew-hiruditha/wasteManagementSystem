<?php
session_start();
require_once '../../classes/DbConnector.php';
require_once '../../classes/User.php';
require_once 'WasteReport.php';
require_once '../collections/CollectionSchedule.php';
require_once '../collections/CollectionRequest.php';

use classes\DbConnector;
use classes\User;
use classes\WasteReport;
use classes\CollectionSchedule;
use classes\CollectionRequest;

// Check if user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    header('Location: ../../index.php');
    exit();
}

$user = new User($_SESSION['user_firstname'], $_SESSION['user_lastname'], $_SESSION['user_name'], '', 'user');
$user->setId($_SESSION['user_id']);

$conn = DbConnector::getConnection();

// Handle waste report submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_report'])) {
    $wasteType = $_POST['waste_type'];
    $amount = $_POST['amount'];
    $report = new WasteReport($user->getId(), $wasteType, $amount);
    $report->save($conn);
}

// Handle collection request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_request'])) {
    $requestDate = $_POST['request_date'];
    $request = new CollectionRequest($user->getId(), $requestDate);
    $request->save($conn);
}

// Fetch user's waste reports
$userReports = WasteReport::getReportsByUserId($conn, $user->getId());

// Fetch collection schedule
$schedule = CollectionSchedule::getUpcomingSchedule($conn);

// Fetch user's collection requests
$userRequests = CollectionRequest::getRequestsByUserId($conn, $user->getId());

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Waste Management System</title>
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
        <h1>Welcome, <?php echo htmlspecialchars($user->getFirst_name()); ?>!</h1>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <h2>Submit Waste Report</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="waste_type" class="form-label">Waste Type</label>
                        <select class="form-select" id="waste_type" name="waste_type" required>
                            <option value="Organic">Organic</option>
                            <option value="Recyclable">Recyclable</option>
                            <option value="Hazardous">Hazardous</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (kg)</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                    <button type="submit" name="submit_report" class="btn btn-primary">Submit Report</button>
                </form>
            </div>
            
            <div class="col-md-6">
                <h2>Request Collection</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="request_date" class="form-label">Preferred Collection Date</label>
                        <input type="date" class="form-control" id="request_date" name="request_date" required>
                    </div>
                    <button type="submit" name="submit_request" class="btn btn-primary">Request Collection</button>
                </form>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <h2>Upcoming Collection Schedule</h2>
                <ul class="list-group">
                    <?php foreach ($schedule as $collection): ?>
                        <li class="list-group-item">
                            <?php echo htmlspecialchars($collection['area']); ?> - 
                            <?php echo htmlspecialchars($collection['date']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="col-md-6">
                <h2>Your Collection Requests</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Request Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userRequests as $request): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($request['request_date']); ?></td>
                                <td><?php echo htmlspecialchars($request['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <h2>Your Waste Reports</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Waste Type</th>
                            <th>Amount (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userReports as $report): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($report['date']); ?></td>
                                <td><?php echo htmlspecialchars($report['waste_type']); ?></td>
                                <td><?php echo htmlspecialchars($report['amount']); ?></td>
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