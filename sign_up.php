<?php
$message = null;
if (isset($_GET["status"])) {
    $status = $_GET["status"];

    if ($status == 0) {
        $message = "<h6 class='alert alert-danger'>Required values were not submitted</h6>";
    } elseif ($status == 1) {
        $message = "<h6 class='alert alert-warning'>You must fill all fields to register with Waste Management System</h6>";
    } elseif ($status == 2) {
        $message = "<h6 class='alert alert-success'>You are successfully registered with Waste Management System</h6>";
    } elseif ($status == 3) {
        $message = "<h6 class='alert alert-danger'>Error occurred during the registration. Please try again</h6>";
    } elseif ($status == 4) {
        $message = "<h6 class='alert alert-danger'>Passwords do not match. Please try again</h6>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign Up - Waste Management System</title>
        <link rel="stylesheet" href="css/signup.css">
        <link rel="stylesheet" href="css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
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
                <div class="col-lg-6 p-0 d-none d-lg-block">
                    <div class="left-section">
                        <h1 class="display-4 mb-4">Welcome to Waste Management System</h1>
                        <p class="lead">Join the waste revolution. Sign up now!</p>
                    </div>
                </div>
                <div class="col-lg-6 p-0">
                    <div class="right-section">
                        <div class="form-container">
                            <h2 class="mb-4">Create Your Account</h2>
                            <?php
                            if (isset($message)) {
                                echo $message;
                            }
                            ?>
                            <form action="registration.php" method="POST">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="First name" name="firstname" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Last name" name="lastname" required>
                                    </div>
                                </div>
                                <input type="email" class="form-control" placeholder="Email address" name="username" required>
                                <input type="tel" class="form-control" placeholder="Mobile Number" name="mobile" required>
                                <h5 class="mt-4 mb-3">Address</h5>
                                <input type="text" class="form-control" placeholder="Street Address" name="street" required>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="City" name="city" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="State/Province" name="state" required>
                                    </div>
                                </div>
                                <input type="text" class="form-control" placeholder="Postal Code" name="postalcode" required>
                                <hr>
                                <input type="password" class="form-control" placeholder="Password" name="password" required>
                                <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" required>



<!--                            <select class="form-select" name="role" required>
    <option value="" disabled selected>Select your role</option>
    <option value="user">Resident</option>
    <option value="driver">Waste Collector</option>
</select>-->


                                <button type="submit" class="btn btn-primary btn-lg w-100 custom-btn">Create Account</button>
                            </form>
                            <p class="mt-3 text-center">Already have an account? <a href="index.php">Sign in</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>