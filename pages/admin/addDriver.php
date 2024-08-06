<?php
session_start();
require_once '../../classes/DbConnector.php';
require_once '../../classes/User.php';

use classes\DbConnector;
use classes\User;

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

$currentPage = 'addDriver';

$status_messages = [
    0 => "Invalid form submission.",
    1 => "All fields are required.",
    2 => "Driver registered successfully!",
    3 => "Registration failed. Please try again.",
    4 => "Passwords do not match.",
    5 => "Invalid mobile number. Please enter a 10-digit number.",
    6 => "Invalid postal code. Please enter a 5-digit number.",
    7 => "Username already exists. Please choose a different email.",
    8 => "Password must be at least 8 characters long and contain at least one special character."
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Driver - Waste Management System</title>
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
        }
        .form-control:focus, .form-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }
        .form-control {
            border-left: none;
        }
        .form-control:focus {
            box-shadow: none;
        }
        .input-group:focus-within .input-group-text {
            border-color: #80bdff;
        }
        .input-group:focus-within .form-control {
            border-color: #80bdff;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'nav.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content-wrapper">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Add Driver</h1>
                </div>

                <?php
                $message = null;
                if (isset($_GET["status"])) {
                    $status = $_GET["status"];

                    if ($status == 0) {
                        $message = "<h6 class='alert alert-danger'>Required values were not submitted</h6>";
                    } elseif ($status == 1) {
                        $message = "<h6 class='alert alert-warning'>You must fill all fields to register a driver</h6>";
                    } elseif ($status == 2) {
                        $message = "<h6 class='alert alert-success'>Driver successfully registered</h6>";
                    } elseif ($status == 3) {
                        $message = "<h6 class='alert alert-danger'>Error occurred during the registration. Please try again</h6>";
                    } elseif ($status == 4) {
                        $message = "<h6 class='alert alert-danger'>Passwords do not match. Please try again</h6>";
                    } elseif ($status == 5) {
                        $message = "<h6 class='alert alert-danger'>Invalid mobile number. Please try again</h6>";
                    } elseif ($status == 6) {
                        $message = "<h6 class='alert alert-danger'>Invalid postal code. Please try again</h6>";
                    } elseif ($status == 7) {
                        $message = "<h6 class='alert alert-danger'>Email address is already registered. Please try again</h6>";
                    } elseif ($status == 8) {
                        $message = "<h6 class='alert alert-danger'>Password must be at least 8 characters long and include at least one special character. Please try again.</h6>";
                    }
                }

                if (isset($message)) {
                    echo $message;
                }
                ?>

                <div class="card">
                    <div class="card-body">
                        <form id="addDriverForm" method="POST" action="addDriverProcess.php">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstname" class="form-label">First Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="firstname" name="firstname" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastname" class="form-label">Last Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="lastname" name="lastname" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username (Email)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="username" name="username" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                    <input type="tel" class="form-control" id="mobile" name="mobile" required>
                                </div>
                            </div>
                            <h5 class="mt-4 mb-3">Address</h5>
                            <div class="mb-3">
                                <label for="street" class="form-label">Street Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-house"></i></span>
                                    <input type="text" class="form-control" id="street" name="street" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-building"></i></span>
                                        <input type="text" class="form-control" id="city" name="city" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="state" class="form-label">State/Province</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-geo"></i></span>
                                        <input type="text" class="form-control" id="state" name="state" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="postalcode" class="form-label">Postal Code</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-mailbox"></i></span>
                                    <input type="text" class="form-control" id="postalcode" name="postalcode" required>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100"><i class="bi bi-person-plus-fill me-2"></i>Create Account</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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