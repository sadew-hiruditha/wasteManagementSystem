<?php
session_start();

if (isset($_SESSION["user_id"])) {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>Admin Dashboard</title>
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            padding: 48px 0 0;
            overflow-x: hidden;
            overflow-y: auto;
            background-color: #343a40;
            height: 100vh;
        }

        .sidebar-heading {
            color: #fff;
            font-size: 1.2rem;
            padding: 0 16px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 10px 16px;
        }

        .nav-link:hover {
            color: #fff;
            text-decoration: none;
        }

        .content {
            padding: 20px;
        }

        .card {
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .content {
                padding: 10px;
            }

            .bottom-nav {
                display: flex;
                justify-content: space-around;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background-color: #343a40;
                z-index: 1000;
            }

            .bottom-nav .nav-link {
                padding: 10px;
                color: #fff;
            }

            .bottom-nav .nav-link:hover {
                color: #fff;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-md-3 col-lg-2 d-none d-md-block sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#manageUsers">Manage Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#manageRoutes">Manage Routes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#assignRoutes">Assign Routes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#pickupHistory">Pickup History</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="sign_out.php">Sign Out</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Welcome to the Admin Dashboard <span class="name"><?= $_SESSION["user_firstname"] . " " . $_SESSION["user_lastname"] ?></span></h2>
                </div>

                <div class="row">
                    <!-- Cards Section -->
                    <div class="col-md-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <h5 class="card-title">Total Users</h5>
                                <p class="card-text">100</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h5 class="card-title">Total Drivers</h5>
                                <p class="card-text">50</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <h5 class="card-title">Total Routes</h5>
                                <p class="card-text">20</p>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- Bottom Navigation for Mobile -->
    <nav class="bottom-nav d-md-none">
        <a class="nav-link" href="#manageUsers"><i class="bi bi-people"></i></a>
        <a class="nav-link" href="#manageRoutes"><i class="bi bi-map"></i></a>
        <a class="nav-link" href="#assignRoutes"><i class="bi bi-clipboard"></i></a>
        <a class="nav-link" href="#pickupHistory"><i class="bi bi-clock-history"></i></a>
        <a class="nav-link" href="sign_out.php"><i class="bi bi-box-arrow-right"></i></a>
    </nav>

    <!-- Bootstrap JS (optional, for certain components that require JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YdInl8o8XGksR5tD2nMrZ8WxEBQHV+WjHFrTL9pTNXTGeRKsl9n6r9TnP/jTTTyg" crossorigin="anonymous"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">

</body>

</html>
<?php
} else {
    header("Location:../../");
}
?>
