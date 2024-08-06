

<?php
session_start();
require_once '../../classes/DbConnector.php';
require_once '../../classes/User.php';
require_once '../../classes/WasteRequest.php';

use classes\DbConnector;
use classes\User;
use classes\WasteRequest;

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}

// Create a User object with the session data
$user = new User(
    $_SESSION['user_firstname'],
    $_SESSION['user_lastname'],
    $_SESSION['user_username'],
    '',
    $_SESSION['user_mobile'],
    $_SESSION['user_street'],
    $_SESSION['user_city'],
    $_SESSION['user_state'],
    $_SESSION['user_postalcode']
);
$user->setId($_SESSION['user_id']);

$dbcon = new DbConnector();
$con = $dbcon->getConnection();

$totalUsers = User::getTotalUsersCount($con);
$totalDrivers = User::getTotalDriversCount($con);
$totalRequests = WasteRequest::getTotalRequestsCount($con);
$pendingCount = WasteRequest::getPendingRequestCountforAll($con);
$approvedCount = WasteRequest::getApprovedRequestCountforAll($con);
$completedCount = WasteRequest::getCompletedRequestCountforAll($con);

$currentPage = 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Waste Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .content-wrapper {
            padding-top: 20px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .avatar {
            width: 60px;
            height: 60px;
            background-color: #007bff;
            color: white;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'nav.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content-wrapper">
<!--                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Admin Dashboard</h1>
                </div>-->

                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <div class=" bg-light">
                            <div class="card-body d-flex align-items-center">
                                <div class="avatar me-3">
                                    <?php echo strtoupper(substr($user->getFirst_name(), 0, 1) . substr($user->getLast_name(), 0, 1)); ?>
                                </div>
                                <div>
                                    <h2 class="card-title mb-0">Welcome back, <?php echo htmlspecialchars($user->getFirst_name()); ?>!</h2>
                                    <p class="text-muted mb-0">Here's an overview of the waste management system.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-4 col-lg-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-people card-icon"></i>
                                <h5 class="card-title">Total Users</h5>
                                <p class="card-text display-4"><?php echo $totalUsers; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-truck card-icon"></i>
                                <h5 class="card-title">Total Drivers</h5>
                                <p class="card-text display-4"><?php echo $totalDrivers; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-list-check card-icon"></i>
                                <h5 class="card-title">Total Requests</h5>
                                <p class="card-text display-4"><?php echo $totalRequests; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-hourglass-split card-icon"></i>
                                <h5 class="card-title">Pending Requests</h5>
                                <p class="card-text display-4"><?php echo $pendingCount; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="card bg-secondary text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-check-circle card-icon"></i>
                                <h5 class="card-title">Approved Requests</h5>
                                <p class="card-text display-4"><?php echo $approvedCount; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="card bg-dark text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-flag card-icon"></i>
                                <h5 class="card-title">Completed Requests</h5>
                                <p class="card-text display-4"><?php echo $completedCount; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
       <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        $(".navbar-toggler").click(function () {
            $("#sidebar").toggleClass("show");
        });
        // Close sidebar when clicking outside on mobile
        $(document).click(function (event) {
            if (!$(event.target).closest('#sidebar, .navbar-toggler').length) {
                $("#sidebar").removeClass("show");
            }
        });
    </script>
</body>
</html>