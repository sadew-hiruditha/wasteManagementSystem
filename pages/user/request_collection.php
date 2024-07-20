<?php
session_start();
require_once '../../classes/DbConnector.php';
require_once '../../classes/User.php';

use classes\User;

// Check if user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
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

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Request Collection</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                        <?php include 'nav.php'; ?>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content-wrapper">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Dashboard</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="userDashboard.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Request Collection</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="content">
                        <div class="container mt-5">
                            <h2 class="mb-4">Request Waste Collection</h2>

                             <?php if ($message): ?>
                        <div class="alert alert-info"><?php echo $message; ?></div>
                    <?php endif; ?>

                            <form action="process_request.php" method="post">
                                <div class="mb-3">
                                    <label for="waste_type" class="form-label">Waste Type</label>
                                    <select class="form-select" id="waste_type" name="waste_type" required>
                                        <option value="">Select Waste Type</option>
                                        <option value="General">General Waste</option>
                                        <option value="Recyclable">Recyclable</option>
                                        <option value="Organic">Organic</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="text" class="form-control" id="quantity" name="quantity" required>
                                </div>

                                <div class="mb-3">
                                    <label for="preferred_date" class="form-label">Preferred Collection Date</label>
                                    <input type="date" class="form-control" id="preferred_date" name="preferred_date" required>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Additional Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit Request</button>
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
        </script>





        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>