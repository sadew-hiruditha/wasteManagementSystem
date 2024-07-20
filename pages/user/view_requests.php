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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content-wrapper {
            padding-top: 20px;
        }
        .modal-content {
            border-radius: 10px;
        }
        .btn-primary, .btn-danger {
            margin-right: 5px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
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
                            <li class="breadcrumb-item active" aria-current="page">View Requests</li>
                        </ol>
                    </nav>
                </div>
                <div class="content">
                    <div class="container mt-4">
                        <h2 class="mb-4">View Waste Collection Requests</h2>

                        <?php if ($message): ?>
                            <div class="alert alert-info"><?php echo $message; ?></div>
                        <?php endif; ?>

                        <?php if (empty($requests)): ?>
                            <p>You haven't made any waste collection requests yet.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Request ID</th>
                                            <th>Waste Type</th>
                                            <th>Quantity</th>
                                            <th>Preferred Date</th>
                                            <th>Notes</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($requests as $request): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($request['id']); ?></td>
                                                <td><?php echo htmlspecialchars($request['waste_type']); ?></td>
                                                <td><?php echo htmlspecialchars($request['quantity']); ?></td>
                                                <td><?php echo htmlspecialchars($request['preferred_date']); ?></td>
                                                <td><?php echo htmlspecialchars($request['notes']); ?></td>
                                                <td><?php echo htmlspecialchars($request['status'] ?? 'Pending'); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary edit-btn" data-id="<?php echo $request['id']; ?>">Edit</button>
                                                    <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $request['id']; ?>">Delete</button>
                                                </td>
                                            </tr>
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="process_request_action.php" method="post">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="request_id" id="edit_request_id">
                        <div class="mb-3">
                            <label for="edit_waste_type" class="form-label">Waste Type</label>
                            <select class="form-select" id="edit_waste_type" name="waste_type" required>
                                <option value="General">General Waste</option>
                                <option value="Recyclable">Recyclable</option>
                                <option value="Organic">Organic</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_quantity" class="form-label">Quantity</label>
                            <input type="text" class="form-control" id="edit_quantity" name="quantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_preferred_date" class="form-label">Preferred Collection Date</label>
                            <input type="date" class="form-control" id="edit_preferred_date" name="preferred_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this waste collection request?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" action="process_request_action.php" method="post">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="request_id" id="delete_request_id">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
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

        // Edit button click handler
        $('.edit-btn').click(function () {
            var requestId = $(this).data('id');
            // Fetch request details using AJAX
            $.ajax({
                url: 'get_request_details.php',
                method: 'GET',
                data: {id: requestId},
                dataType: 'json',
                success: function (response) {
                    $('#edit_request_id').val(response.id);
                    $('#edit_waste_type').val(response.waste_type);
                    $('#edit_quantity').val(response.quantity);
                    $('#edit_preferred_date').val(response.preferred_date);
                    $('#edit_notes').val(response.notes);
                    $('#editModal').modal('show');
                },
                error: function () {
                    alert('Error fetching request details');
                }
            });
        });

        // Delete button click handler
        $('.delete-btn').click(function () {
            var requestId = $(this).data('id');
            $('#delete_request_id').val(requestId);
            $('#deleteModal').modal('show');
        });
    </script>
</body>
</html>
