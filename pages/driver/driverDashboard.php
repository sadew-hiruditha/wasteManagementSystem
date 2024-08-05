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

$query = "SELECT DISTINCT u.id, CONCAT(u.firstname, ' ', u.lastname) AS customer_name, 
          (SELECT COUNT(*) FROM waste_requests wr WHERE wr.user_id = u.id AND wr.status IN ('Pending', 'Approved')) AS request_count
          FROM users u
          JOIN waste_requests wr ON u.id = wr.user_id
          WHERE wr.status IN ('Pending', 'Approved')
          ORDER BY u.firstname, u.lastname";

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
     
            <main class=" ms-sm-auto col-lg-12">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="bi bi-speedometer2 me-2"></i>Driver Dashboard</h1>
                    
                    <a class="nav-link" href="../components/sign_out.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                 
                </div>

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
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="bi bi-person me-2"></i>
                                            <?php echo htmlspecialchars($user['customer_name']); ?>
                                        </h5>
                                        <p class="card-text">
                                            <i class="bi bi-clipboard-check me-2"></i>
                                            Pending/Approved Requests: <span class="badge bg-primary"><?php echo $user['request_count']; ?></span>
                                        </p>
                                        <a href="driverRequestList.php?user_id=<?php echo $user['id']; ?>" class="btn btn-outline-primary">
                                            <i class="bi bi-eye me-2"></i>View Requests
                                        </a>
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