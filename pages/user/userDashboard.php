<?php
session_start();
require_once '../../classes/DbConnector.php';
require_once '../../classes/User.php';
use classes\DbConnector;
use classes\User;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit();
}

// Create a User object with the session data
$user = new User(
    $_SESSION['user_firstname'],
    $_SESSION['user_lastname'],
    $_SESSION['user_username'], // This is now the email
    '', // Password is not needed here
    $_SESSION['user_mobile'],
    $_SESSION['user_street'],
    $_SESSION['user_city'],
    $_SESSION['user_state'],
    $_SESSION['user_postalcode']
);
$user->setId($_SESSION['user_id']);
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
    <div class="container-fluid">
        <div class="row">
            <?php include 'nav.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content-wrapper">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>
                <div class="content">
                    <div class="container mt-5">
                        <h2>Welcome, <?php echo htmlspecialchars($user->getFirst_name() . ' ' . $user->getLast_name()); ?></h2>
                        <p>Email: <?php echo htmlspecialchars($user->getUsername()); ?></p>
                        <p>Mobile: <?php echo htmlspecialchars($_SESSION['user_mobile']); ?></p>
                        <p>Address: <?php echo htmlspecialchars($_SESSION['user_street'] . ', ' . $_SESSION['user_city'] . ', ' . $_SESSION['user_state'] . ' ' . $_SESSION['user_postalcode']); ?></p>
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
    </script>
</body>
</html>