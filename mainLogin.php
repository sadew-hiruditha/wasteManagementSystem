<?php
session_start();
$message = null;
if (isset($_GET["status"])) {
    $status = $_GET["status"];

    if ($status === '0') {
        $message = '<div class="alert alert-danger" role="alert"><h6>Required values were not submitted</h6></div>';
    } elseif ($status === '1') {
        $message = '<div class="alert alert-danger" role="alert"><h6>You must fill all fields to login</h6></div>';
    } elseif ($status === '2') {
        $message = '<div class="alert alert-danger" role="alert"><h6>Incorrect username or password</h6></div>';
    } elseif ($status === 'unauthorized') {
        $message = '<div class="alert alert-warning" role="alert"><h6>Unauthorized access. Please log in with the correct role.</h6></div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .form-control {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }

        .custom-btn {
            background-color: #28a745;
            border-color: #28a745;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .custom-btn:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Left Section -->
            <div class="col-lg-6 p-0 d-none d-lg-block">
                <div class="left-section">
                    <h1 class="display-4 mb-4">Welcome Back To <a href="index.php">GreenPath</a></h1>
                    <p class="lead">Join the waste revolution. Sign In now!</p>
                </div>
            </div>

            <!-- Right Section -->
            <div class="col-lg-6 p-0 ">
                <div class="right-section">
                    <div class="signin-form">
                        <h3>Login to the System</h3>
                        <?php echo $message; ?>
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Email address" name="username">
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" placeholder="Password" name="password">
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                                <label class="form-check-label" for="rememberMe">Remember me</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 custom-btn">Sign in</button>
                            <p class="form-footer">Don't have an account? <a href="sign_up.php">Sign up</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>