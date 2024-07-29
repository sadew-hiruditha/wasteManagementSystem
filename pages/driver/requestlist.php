<?php
session_start();
require_once '../../classes/DbConnector.php';
require_once '../../classes/Driver.php';
require_once '../../classes/User.php';
require_once '../../classes/WasteRequest.php';

use classes\DbConnector;
use classes\User;
use classes\Driver;
use classes\WasteRequests;

// Check if user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'driver') {
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

// Fetch cosllection requests
$dbConnector = new DbConnector();
$conn = $dbConnector->getConnection();
$wasteRequests = WasteRequests::getAllRequests($conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Request List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'nav.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Collection Request List</h1>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Waste Type</th>
                                <th>Quantity</th>
                                <th>Preferred Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($wasteRequests as $request): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($request['id']); ?></td>
                                    <td><?php echo htmlspecialchars($request['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($request['waste_type']); ?></td>
                                    <td><?php echo htmlspecialchars($request['quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($request['preferred_date']); ?></td>
                                    <td><?php echo htmlspecialchars($request['status']); ?></td>
                                    <td>
                                        <?php if ($request['status'] == 'Pending'): ?>
                                            <a href="updatePickup.php?id=<?php echo $request['id']; ?>&action=complete" class="btn btn-sm btn-success">Complete</a>
                                            <a href="updatePickup.php?id=<?php echo $request['id']; ?>&action=cancel" class="btn btn-sm btn-danger">Cancel</a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>