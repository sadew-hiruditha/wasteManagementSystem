<?php
session_start();
require_once '../../classes/DbConnector.php';
require_once '../../classes/User.php';
require_once '../../classes/WasteRequest.php';

use classes\DbConnector;
use classes\User;
use classes\WasteRequest;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
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

$pendingCount = WasteRequest::getPendingRequestCount($con, $user->getId());
$approvedCount = WasteRequest::getApprovedRequestCount($con, $user->getId());
$completedCount = WasteRequest::getCompletedRequestCount($con, $user->getId());

// Handle form submission for updating user information
$updateMessage = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_info'])) {
        $user->setMobile($_POST['mobile']);
        $user->setStreet($_POST['street']);
        $user->setCity($_POST['city']);
        $user->setState($_POST['state']);
        $user->setPostalcode($_POST['postalcode']);

        // Implement the update method in the User class
        if ($user->updateUserInfo($con)) {
            $updateMessage = 'User information updated successfully.';
            // Update session variables
            $_SESSION['user_mobile'] = $user->getMobile();
            $_SESSION['user_street'] = $user->getStreet();
            $_SESSION['user_city'] = $user->getCity();
            $_SESSION['user_state'] = $user->getState();
            $_SESSION['user_postalcode'] = $user->getPostalcode();
        } else {
            $updateMessage = 'Failed to update user information.';
        }
    }
}

$currentPage = 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Waste Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.5rem;
            height: 3.5rem;
            font-size: 1.5rem;
            border-radius: 50%;
        }
        .avatar-xl {
            width: 5rem;
            height: 5rem;
            font-size: 2rem;
        }
        .avatar-initial {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: bold;
        }
        .editable-field {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }
        .editable-field.editing {
            background-color: #ffffff;
            cursor: text;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <?php include 'nav.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content-wrapper">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>
                <?php if ($updateMessage): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?php echo $updateMessage; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <div class="avatar avatar-xl">
                                    <span class="avatar-initial rounded-circle bg-primary">
                                        <?php echo strtoupper(substr($user->getFirst_name(), 0, 1) . substr($user->getLast_name(), 0, 1)); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col">
                                <h2 class="card-title mb-0">Welcome back, <?php echo htmlspecialchars($user->getFirst_name()); ?>!</h2>
                                <p class="text-muted mb-0">We're glad to see you again.</p>
                            </div>
                        </div>
                               <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
                    <div class="col">
                        <div class="card h-100 bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-hourglass me-2"></i>Pending Requests</h5>
                                <p class="card-text display-4"><?php echo $pendingCount; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100 bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-check-circle me-2"></i>Approved Requests</h5>
                                <p class="card-text display-4"><?php echo $approvedCount; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100 bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-flag me-2"></i>Completed Requests</h5>
                                <p class="card-text display-4"><?php echo $completedCount; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                        <form id="userInfoForm" method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label"><i class="bi bi-envelope-fill text-primary me-2"></i>Email Address</label>
                                    <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user->getUsername()); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="mobile" class="form-label"><i class="bi bi-phone-fill text-primary me-2"></i>Mobile Number</label>
                                    <input type="tel" class="form-control editable-field" id="mobile" name="mobile" value="<?php echo htmlspecialchars($user->getMobile()); ?>" readonly required>
                                </div>
                                <div class="col-md-6">
                                    <label for="street" class="form-label"><i class="bi bi-house-fill text-primary me-2"></i>Street</label>
                                    <input type="text" class="form-control editable-field" id="street" name="street" value="<?php echo htmlspecialchars($user->getStreet()); ?>" readonly required>
                                </div>
                                <div class="col-md-6">
                                    <label for="city" class="form-label"><i class="bi bi-building text-primary me-2"></i>City</label>
                                    <input type="text" class="form-control editable-field" id="city" name="city" value="<?php echo htmlspecialchars($user->getCity()); ?>" readonly required>
                                </div>
                                <div class="col-md-6">
                                    <label for="state" class="form-label"><i class="bi bi-geo-alt-fill text-primary me-2"></i>State</label>
                                    <input type="text" class="form-control editable-field" id="state" name="state" value="<?php echo htmlspecialchars($user->getState()); ?>" readonly required>
                                </div>
                                <div class="col-md-6">
                                    <label for="postalcode" class="form-label"><i class="bi bi-mailbox text-primary me-2"></i>Postal Code</label>
                                    <input type="text" class="form-control editable-field" id="postalcode" name="postalcode" value="<?php echo htmlspecialchars($user->getPostalcode()); ?>" readonly required>
                                </div>
                                <div class="col-12">
                                    <button type="button" id="editButton" class="btn btn-primary">
                                        <i class="bi bi-pencil-square me-2"></i>Edit Information
                                    </button>
                                    <button type="submit" id="updateButton" name="update_info" class="btn btn-success d-none">
                                        <i class="bi bi-check-circle me-2"></i>Update Information
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
         
            </main>
        </div>
    </div>

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

        // Add new JavaScript for edit functionality
        $(document).ready(function() {
            $("#editButton").click(function() {
                $(".editable-field").prop("readonly", false).addClass("editing");
                $(this).addClass("d-none");
                $("#updateButton").removeClass("d-none");
            });
        });
    </script>
</body>
</html>