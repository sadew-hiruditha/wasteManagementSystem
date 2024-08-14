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

// Fetch all users with pending or approved requests
$dbConnector = new DbConnector();
$conn = $dbConnector->getConnection();

// Handle user acceptance
if (isset($_POST['accept_user'])) {
    $user_id = $_POST['user_id'];
    $driver_id = $_SESSION['user_id'];

    $result = User::assignDriver($conn, $user_id, $driver_id);

    if ($result) {
        $_SESSION['success_message'] = "User accepted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to accept user.";
    }

    // Redirect to the same page to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
// Handle user acceptance removal
if (isset($_POST['remove_accept'])) {
    $user_id = $_POST['user_id'];

    $result = User::removeDriverAssignment($conn, $user_id);

    if ($result) {
        $_SESSION['success_message'] = "User acceptance removed successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to remove user acceptance.";
    }

    // Redirect to the same page to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$query = "SELECT DISTINCT u.id, CONCAT(u.firstName, ' ', u.lastName) AS customer_name, 
          (SELECT COUNT(*) FROM waste_requests wr WHERE wr.user_id = u.id AND wr.status IN ('Pending', 'Approved')) AS request_count,
          u.assigned_driver_id
          FROM users u
          JOIN waste_requests wr ON u.id = wr.user_id
          WHERE wr.status IN ('Pending', 'Approved')
          ORDER BY u.firstName, u.lastName";

$stmt = $conn->prepare($query);

try {
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$currentPage = 'driver_dashboard';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Driver Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
        <style>
            .card {
                transition: transform 0.3s, box-shadow 0.3s;
            }
            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            }
            .bg-gradient-primary {
                background: linear-gradient(45deg, #007bff, #00bcd4);
            }
        </style>
    </head>
    <body class="bg-light">
        <div class="container-fluid">
            <div class="row">
                <main class="ms-sm-auto col-lg-12">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2"><i class="bi bi-speedometer2 me-2"></i>Driver Dashboard</h1>

                        <a class="btn btn-outline-primary" href="../components/sign_out.php">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>

                  <div class="bg-white shadow-sm rounded-3 p-4 mb-4">
    <div class="d-flex align-items-center">
        <div class="flex-shrink-0">
            <?php
            $initials = strtoupper(substr($_SESSION['user_firstname'], 0, 1) . substr($_SESSION['user_lastname'], 0, 1));
            $bgColor = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            ?>
            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center" style="width: 64px; height: 64px; font-size: 24px; font-weight: bold;">
                <?php echo $initials; ?>
            </div>
        </div>
        <div class="flex-grow-1 ms-3">
            <h2 class="fw-bold mb-1">Welcome back, <?php echo htmlspecialchars($_SESSION['user_firstname']); ?>!</h2>
            <p class="text-muted mb-0">Ready to manage your waste collection routes?</p>
        </div>
    </div>
</div>

                    <?php
                    // Display success or error message if set
                    if (isset($_SESSION['success_message'])) {
                        echo "<div class='alert alert-success'>" . $_SESSION['success_message'] . "</div>";
                        unset($_SESSION['success_message']);
                    }
                    if (isset($_SESSION['error_message'])) {
                        echo "<div class='alert alert-danger'>" . $_SESSION['error_message'] . "</div>";
                        unset($_SESSION['error_message']);
                    }
                    ?>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-gradient-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-person-check me-2"></i>Total Customers</h5>
                                    <p class="card-text display-4"><?php echo count($users); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-list-check me-2"></i>Total Requests</h5>
                                    <p class="card-text display-4"><?php echo array_sum(array_column($users, 'request_count')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-dark">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-clock-history me-2"></i>Pending Requests</h5>
                                    <p class="card-text display-4">
                                        <?php
                                        $pendingQuery = "SELECT COUNT(*) FROM waste_requests WHERE status = 'Pending'";
                                        $pendingStmt = $conn->query($pendingQuery);
                                        echo $pendingStmt->fetchColumn();
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h2 class="mb-4"><i class="bi bi-people me-2"></i>Customers with Requests</h2>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <div class="col">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">
                                                <i class="bi bi-person me-2"></i>
                                                <?php echo htmlspecialchars($user['customer_name']); ?>
                                            </h5>
                                            <p class="card-text">
                                                <i class="bi bi-clipboard-check me-2"></i>
                                                Pending/Approved Requests: <span class="badge bg-primary"><?php echo $user['request_count']; ?></span>
                                            </p>
                                            <div class="mt-auto">
                                                <a href="driverRequestList.php?user_id=<?php echo $user['id']; ?>" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                                    <i class="bi bi-eye me-2"></i>View Requests
                                                </a>
                                                <?php if (is_null($user['assigned_driver_id'])): ?>
                                                    <form method="POST">
                                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                        <button type="submit" name="accept_user" class="btn btn-success btn-sm w-100">
                                                            <i class="bi bi-check-circle me-2"></i>Accept user
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-success">
                                                            <i class="bi bi-person-check-fill me-2"></i>Accepted by: 
                                                            <?php
                                                            $driver = User::getUserById($conn, $user['assigned_driver_id']);
                                                            echo $driver ? htmlspecialchars($driver->getFirst_name() . ' ' . $driver->getLast_name()) : 'Unknown Driver';
                                                            ?>
                                                        </small>
                                                        <form method="POST">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <button type="submit" name="remove_accept" class="btn btn-outline-danger btn-sm">
                                                                <i class="bi bi-x-circle"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>No users with pending or approved requests found.
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>