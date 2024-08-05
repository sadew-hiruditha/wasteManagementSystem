<?php
session_start();
require_once '../../classes/DbConnector.php';
require_once '../../classes/Driver.php';
require_once '../../classes/User.php';
require_once '../../classes/WasteRequest.php';

use classes\DbConnector;
use classes\User;
use classes\Driver;
use classes\WasteRequest;

// Check if user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'driver') {
    header('Location: ../../index.php');
    exit();
}

// Check if user_id is provided
if (!isset($_GET['user_id'])) {
    header('Location: driverDashboard.php');
    exit();
}

$userId = $_GET['user_id'];

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

// Handle status updates
$dbConnector = new DbConnector();
$conn = $dbConnector->getConnection();

$message = '';
if (isset($_POST['action']) && isset($_POST['request_id'])) {
    $action = $_POST['action'];
    $requestId = $_POST['request_id'];

    if ($action === 'accept') {
        WasteRequest::updateRequestStatus($conn, $requestId, 'Approved');
        $message = "Request approved successfully.";
    } elseif ($action === 'complete') {
        WasteRequest::updateRequestStatus($conn, $requestId, 'Completed');
        $message = "Request marked as completed successfully.";
    }
}

// Fetch user details
$userQuery = "SELECT firstname, lastname, username, mobile, street, city, state, postalcode FROM users WHERE id = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->execute([$userId]);
$customerDetails = $userStmt->fetch(PDO::FETCH_ASSOC);

// Fetch user's waste requests
$requestQuery = "SELECT * FROM waste_requests WHERE user_id = ? AND status IN ('Pending', 'Approved') ORDER BY preferred_date ASC";
$requestStmt = $conn->prepare($requestQuery);
$requestStmt->execute([$userId]);
$requests = $requestStmt->fetchAll(PDO::FETCH_ASSOC);

$currentPage = 'driver_request_list';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Request List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .bg-gradient-primary {
            background: linear-gradient(45deg, #007bff, #00bcd4);
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
       
            <main class=" ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="bi bi-list-check me-2"></i>Request List for <?php echo htmlspecialchars($customerDetails["firstname"] . " " . $customerDetails["lastname"]); ?></h1>
                    <a href="driverDashboard.php" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="bi bi-person-badge me-2"></i>Customer Details</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-envelope-fill text-primary me-3 fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block">Email Address</small>
                                        <span><?php echo htmlspecialchars($customerDetails['username']); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-phone-fill text-primary me-3 fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block">Mobile Number</small>
                                        <span><?php echo htmlspecialchars($customerDetails['mobile']); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-geo-alt-fill text-primary me-3 fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block">Address</small>
                                        <span><?php echo htmlspecialchars($customerDetails['street'] . ', ' . $customerDetails['city']); ?></span>
                                        <span class="d-block"><?php echo htmlspecialchars($customerDetails['state'] . ' ' . $customerDetails['postalcode']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle me-2"></i><?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="bi bi-recycle me-2"></i>Waste Requests</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="bi bi-trash me-2"></i>Waste Type</th>
                                        <th><i class="bi bi-bar-chart me-2"></i>Quantity</th>
                                        <th><i class="bi bi-calendar-event me-2"></i>Preferred Date</th>
                                        <th><i class="bi bi-flag me-2"></i>Status</th>
                                        <th><i class="bi bi-card-text me-2"></i>Notes</th>
                                        <th><i class="bi bi-gear me-2"></i>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($requests as $request): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($request['waste_type']); ?></td>
                                            <td><?php echo htmlspecialchars($request['quantity']); ?></td>
                                            <td><?php echo htmlspecialchars($request['preferred_date']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $request['status'] == 'Pending' ? 'warning' : 'success'; ?>">
                                                    <?php echo htmlspecialchars($request['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#notesModal<?php echo $request['id']; ?>">
                                                    <i class="bi bi-eye me-1"></i> View
                                                </button>
                                            </td>
                                            <td>
                                                <?php if ($request['status'] == 'Pending'): ?>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                                        <input type="hidden" name="action" value="accept">
                                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-check me-1"></i> Approve
                                                        </button>
                                                    </form>
                                                <?php elseif ($request['status'] == 'Approved'): ?>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                                        <input type="hidden" name="action" value="complete">
                                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                                            <i class="bi bi-check-circle me-1"></i> Complete
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                        <!-- Notes Modal -->
                                        <div class="modal fade" id="notesModal<?php echo $request['id']; ?>" tabindex="-1" aria-labelledby="notesModalLabel<?php echo $request['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="notesModalLabel<?php echo $request['id']; ?>">
                                                            <i class="bi bi-card-text me-2"></i>Request Notes
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php echo htmlspecialchars($request['notes']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>