

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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
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
    .modern-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }
    .card-body {
        padding: 2rem;
    }
    .card-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        opacity: 0.8;
    }
    .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .card-text {
        font-size: 2.5rem;
        font-weight: 700;
    }
    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
    }
    .bg-gradient-success {
        background: linear-gradient(45deg, #1cc88a, #13855c);
    }
    .bg-gradient-info {
        background: linear-gradient(45deg, #36b9cc, #258391);
    }
    .bg-gradient-warning {
        background: linear-gradient(45deg, #f6c23e, #dda20a);
    }
    .bg-gradient-secondary {
        background: linear-gradient(45deg, #858796, #60616f);
    }
    .bg-gradient-dark {
        background: linear-gradient(45deg, #5a5c69, #373840);
    }
    .modern-card .card-body {
        color: white;
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
    /* Styles for the welcome message */
    .bg-light {
        background-color: #ffffff !important;
    }
    .rounded-3 {
        border-radius: 0.5rem !important;
    }
    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
    h2 {
        font-size: 1.75rem;
        font-weight: 600;
    }
    .text-muted {
        color: #6c757d !important;
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
        <div class="bg-light p-4 rounded-3 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="avatar me-3">
                    <?php echo strtoupper(substr($user->getFirst_name(), 0, 1) . substr($user->getLast_name(), 0, 1)); ?>
                </div>
                <div>
                    <h2 class="mb-0">Welcome back, <?php echo htmlspecialchars($user->getFirst_name()); ?>!</h2>
                    <p class="text-muted mb-0">Here's an overview of the waste management system.</p>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="col-md-4 col-lg-4">
        <div class="card modern-card bg-gradient-primary">
            <div class="card-body">
                <i class="fas fa-users card-icon"></i>
                <h5 class="card-title">Total Users</h5>
                <p class="card-text"><?php echo $totalUsers; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="card modern-card bg-gradient-success">
            <div class="card-body">
                <i class="fas fa-truck card-icon"></i>
                <h5 class="card-title">Total Drivers</h5>
                <p class="card-text"><?php echo $totalDrivers; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="card modern-card bg-gradient-info">
            <div class="card-body">
                <i class="fas fa-clipboard-list card-icon"></i>
                <h5 class="card-title">Total Requests</h5>
                <p class="card-text"><?php echo $totalRequests; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="card modern-card bg-gradient-warning">
            <div class="card-body">
                <i class="fas fa-hourglass-half card-icon"></i>
                <h5 class="card-title">Pending Requests</h5>
                <p class="card-text"><?php echo $pendingCount; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="card modern-card bg-gradient-secondary">
            <div class="card-body">
                <i class="fas fa-check-circle card-icon"></i>
                <h5 class="card-title">Approved Requests</h5>
                <p class="card-text"><?php echo $approvedCount; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="card modern-card bg-gradient-dark">
            <div class="card-body">
                <i class="fas fa-flag-checkered card-icon"></i>
                <h5 class="card-title">Completed Requests</h5>
                <p class="card-text"><?php echo $completedCount; ?></p>
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