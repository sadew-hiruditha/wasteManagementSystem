<?php
session_start();
require_once '../../classes/DbConnector.php';
require_once '../../classes/User.php';
require_once '../../classes/WasteRequest.php';

use classes\User;
use classes\DbConnector;
use classes\WasteRequest;

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

// Fetch user's waste collection requests
$dbConnector = new DbConnector();
$con = $dbConnector->getConnection();

try {
    $requests = WasteRequest::getRequests($con, $_SESSION['user_id']);
} catch (PDOException $ex) {
    $message = "Error: " . $ex->getMessage();
    $requests = [];
}

$currentPage = 'view_requests';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
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
        .modal-content {
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'nav.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content-wrapper">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                    <h1 class="h2">Waste Collection Requests</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="userDashboard.php">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View Requests</li>
                        </ol>
                    </nav>
                </div>
                
                <?php if ($message): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                             <h5 class="card-title"><i class="bi bi-recycle me-2"></i>Your Requests</h5>
                        <!--<h5 class="mb-0">Your Requests</h5>-->
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#removeAllCompletedModal">
                            <i class="bi bi-trash"></i> Remove All Completed
                        </button>
                    </div>
                    <div class="card-body">
                        <?php if (empty($requests)): ?>
                            <p class="text-muted">You haven't made any waste collection requests yet.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                  <thead class="table-light">
                                    <tr>
                                        <th><i class="bi bi-trash me-2"></i>Waste Type</th>
                                        <th><i class="bi bi-bar-chart me-2"></i>Quantity</th>
                                        <th><i class="bi bi-calendar-event me-2"></i>Preferred Date</th>
                                        <th><i class="bi bi-flag me-2"></i>Notes</th>
                                        <th><i class="bi bi-card-text me-2"></i>Status</th>
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
                                                    <button class="btn btn-sm btn-outline-info btn-action" data-bs-toggle="modal" data-bs-target="#notesModal<?php echo $request['id']; ?>">
                                                        <i class="bi bi-eye"></i> View
                                                    </button>
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
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo $request['status'] === 'Pending' ? 'warning' : ($request['status'] === 'Approved' ? 'success' : 'info'); ?>">
                                                        <?php echo htmlspecialchars($request['status'] ?? 'Pending'); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($request['status'] === 'Pending'): ?>
                                                        <button class="btn btn-sm btn-outline-primary btn-action" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $request['id']; ?>">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger btn-action" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $request['id']; ?>">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>
                                                    <?php elseif ($request['status'] === 'Approved'): ?>
                                                        <button class="btn btn-sm btn-outline-secondary btn-action" disabled>
                                                            <i class="bi bi-lock"></i> Processing
                                                        </button>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-outline-success btn-action" disabled>
                                                            <i class="bi bi-check-circle"></i> Completed
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editModal<?php echo $request['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $request['id']; ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel<?php echo $request['id']; ?>">Edit Request</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="process_request_action.php" method="post">
                                                                <input type="hidden" name="action" value="edit">
                                                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                                                <div class="mb-3">
                                                                    <label for="edit_waste_type<?php echo $request['id']; ?>" class="form-label">Waste Type</label>
                                                                    <select class="form-select" id="edit_waste_type<?php echo $request['id']; ?>" name="waste_type" required>
                                                                        <option value="General" <?php echo $request['waste_type'] === 'General' ? 'selected' : ''; ?>>General Waste</option>
                                                                        <option value="Recyclable" <?php echo $request['waste_type'] === 'Recyclable' ? 'selected' : ''; ?>>Recyclable</option>
                                                                        <option value="Organic" <?php echo $request['waste_type'] === 'Organic' ? 'selected' : ''; ?>>Organic</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="edit_quantity<?php echo $request['id']; ?>" class="form-label">Quantity</label>
                                                                    <input type="text" class="form-control" id="edit_quantity<?php echo $request['id']; ?>" name="quantity" value="<?php echo htmlspecialchars($request['quantity']); ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="edit_preferred_date<?php echo $request['id']; ?>" class="form-label">Preferred Collection Date</label>
                                                                    <input type="date" class="form-control" id="edit_preferred_date<?php echo $request['id']; ?>" name="preferred_date" value="<?php echo htmlspecialchars($request['preferred_date']); ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="edit_notes<?php echo $request['id']; ?>" class="form-label">Additional Notes</label>
                                                                    <textarea class="form-control" id="edit_notes<?php echo $request['id']; ?>" name="notes" rows="3"><?php echo htmlspecialchars($request['notes']); ?></textarea>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delete Confirmation Modal -->
                                            <div class="modal fade" id="deleteModal<?php echo $request['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $request['id']; ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel<?php echo $request['id']; ?>">Confirm Deletion</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete this waste collection request?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <form action="process_request_action.php" method="post">
                                                                <input type="hidden" name="action" value="delete">
                                                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="modal fade" id="removeAllCompletedModal" tabindex="-1" aria-labelledby="removeAllCompletedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeAllCompletedModalLabel">Confirm Removal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to remove all completed waste collection requests? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="process_request_action.php" method="post">
                        <input type="hidden" name="action" value="remove_all_completed">
                        <button type="submit" class="btn btn-danger">Remove All Completed</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

