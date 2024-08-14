


<?php
session_start();
require_once '../../classes/DbConnector.php';
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
        classes\WasteRequest::updateRequestStatus($conn, $requestId, 'Approved');
        $message = "Request approved successfully.";
    } elseif ($action === 'complete') {
        classes\WasteRequest::updateRequestStatus($conn, $requestId, 'Completed');
        $message = "Request marked as completed successfully.";
    }
}


// Fetch collection requests
$dbConnector = new DbConnector();
$conn = $dbConnector->getConnection();
$wasteRequests = WasteRequest::getAllRequests($conn);

// Group requests by user_id
$groupedRequests = [];
foreach ($wasteRequests as $request) {
    $userId = $request['user_id'];
    if (!isset($groupedRequests[$userId])) {
        $groupedRequests[$userId] = [
            'customer_name' => $request['customer_name'],
            'requests' => []
        ];
    }
    $groupedRequests[$userId]['requests'][] = $request;
}

$currentPage = 'collection_requests';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Request List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    
</head>
<style>
        body {
            background-color: #f8f9fa;
        }
        .content-wrapper {
            padding-top: 20px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid rgba(0,0,0,.125);
            padding: 1.5rem;
        }
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
        }
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
    <!-- ... (head content remains the same) ... -->
    <style>
        .customer-name {
            cursor: pointer;
        }
        .customer-name:hover {
            text-decoration: underline;
        }
        .customer-requests {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'nav.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content-wrapper">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                    <h1 class="h2">Collection Request List</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="driverDashboard.php">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Collection Requests</li>
                        </ol>
                    </nav>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Collection Requests by Customer</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($groupedRequests)): ?>
                            <p class="text-muted">There are no collection requests at the moment.</p>
                        <?php else: ?>
                            <?php foreach ($groupedRequests as $userId => $customerData): ?>
                                <div class="customer-group mb-4">
                                    <h4 class="customer-name" onclick="toggleRequests('customer-<?php echo $userId; ?>')">
                                        <?php echo htmlspecialchars($customerData['customer_name']); ?>
                                        <i class="bi bi-chevron-down"></i>
                                    </h4>
                                    <div id="customer-<?php echo $userId; ?>" class="customer-requests">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Waste Type</th>
                                                        <th>Quantity</th>
                                                        <th>Preferred Date</th>
                                                        <th>Status</th>
                                                        <th>Notes</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($customerData['requests'] as $request): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($request['waste_type']); ?></td>
                                                            <td><?php echo htmlspecialchars($request['quantity']); ?></td>
                                                            <td><?php echo htmlspecialchars($request['preferred_date']); ?></td>
                                                            <td><?php echo htmlspecialchars($request['status']); ?></td>
                                                            <td>
                                                                <button class="btn btn-sm btn-outline-info btn-action" data-bs-toggle="modal" data-bs-target="#notesModal<?php echo $request['id']; ?>">
                                                                    <i class="bi bi-eye"></i> View
                                                                </button>
                                                            </td>
                                                            <td>
                                                                <?php if ($request['status'] == 'Pending'): ?>
                                                                    <form method="POST" style="display: inline;">
                                                                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                                                        <input type="hidden" name="action" value="accept">
                                                                        <button type="submit" class="btn btn-sm btn-outline-primary btn-action">
                                                                            <i class="bi bi-check"></i> Approve
                                                                        </button>
                                                                    </form>
                                                                <?php elseif ($request['status'] == 'Approved'): ?>
                                                                    <form method="POST" style="display: inline;">
                                                                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                                                        <input type="hidden" name="action" value="complete">
                                                                        <button type="submit" class="btn btn-sm btn-outline-success btn-action">
                                                                            <i class="bi bi-check-circle"></i> Complete
                                                                        </button>
                                                                    </form>
                                                                <?php else: ?>
                                                                    <button class="btn btn-sm btn-outline-secondary btn-action" disabled>
                                                                        <i class="bi bi-lock"></i> Completed
                                                                    </button>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                        
                                                        <!-- Notes Modal -->
                                                        <div class="modal fade" id="notesModal<?php echo $request['id']; ?>" tabindex="-1" aria-labelledby="notesModalLabel<?php echo $request['id']; ?>" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="notesModalLabel<?php echo $request['id']; ?>">Request Notes</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p><?php echo htmlspecialchars($request['notes']); ?></p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleRequests(customerId) {
            var requestsDiv = document.getElementById(customerId);
            if (requestsDiv.style.display === "none" || requestsDiv.style.display === "") {
                requestsDiv.style.display = "block";
            } else {
                requestsDiv.style.display = "none";
            }
        }
    </script>
</body>
</html>