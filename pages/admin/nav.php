<?php if (!isset($user)): ?>
    <?php
    session_start();
    require_once '../../classes/User.php';

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
    ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/navStyle.css">
    <style> 
    
    </style>
</head>
<body>
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
        <div class="position-sticky">
            <div class="user-info">
                <h5>Welcome, <?php echo htmlspecialchars($user->getFirst_name()); ?>!</h5>
                <p><?php echo htmlspecialchars($user->getRole()); ?></p>
            </div>
            <ul class="nav flex-column mt-4">
                <li class="nav-item">
                    <a class="nav-link" href="adminDashboard.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="addDriver.php">
                        <i class="fas fa-clipboard-check"></i> Add Driver
                    </a>
                </li>
               
              
                <li class="nav-item">
                    <a class="nav-link logout" href="../components/sign_out.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <nav class="navbar navbar-expand-md navbar-light bg-light mb-4 d-md-none">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
</body>
</html>
