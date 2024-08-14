<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="../../css/navStyle.css">
    </head>
    <body>
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <div class="position-sticky">
                <div class="user-info">
                    <h5><?php echo htmlspecialchars($user->getFirst_name()); ?> <?php echo htmlspecialchars($user->getLast_name()); ?></h5>
                    <p>user</p>
                </div>
                <ul class="nav flex-column mt-4">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'dashboard') ? 'active' : ''; ?>" href="userDashboard.php">
                            <i class="fas fa-tachometer-alt"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'request_collection') ? 'active' : ''; ?>" href="request_collection.php">
                            <i class="fas fa-clipboard-check"></i> Request <br> &nbsp; &nbsp; &nbsp; &nbsp; Collection
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'view_requests') ? 'active' : ''; ?>" href="view_requests.php">
                            <i class="fas fa-chalkboard-teacher"></i>View Requests
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
        <!--        <span class="navbar-brand"><%= session.getAttribute("firstname")%></span>-->
            </div>
        </nav>

    </body>
</html>
