<?php
$message = null;
if (isset($_GET["status"])) {
    $status = $_GET["status"];


    if ($status == 0) {
        $message = "<h6 class='alert alert-danger'>Required values were not submitted</h6>";
    } elseif ($status == 1) {
        $message = "<h6 class='alert alert-warning'>You must fill all fields to register with Samadhi bookstore</h6>";
    } elseif ($status == 2) {
        $message = "<h6 class='alert alert-success'>You are successfully registered with Samadhi BookStore</h6>";
    } elseif ($status == 3) {
        $message = "<h6 class='alert alert-danger'>Error occurred during the registration. Please try again</h6>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/signup.css">
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
        .custom-select {
            border-radius: 0;
            border: 2px solid #007bff;
            color: #007bff;
            font-weight: 500;

            padding: 10px;
        }

        .custom-select:focus {
            border-color: #0056b3;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Left Section -->
            <div class="col-lg-6 p-0 d-none d-lg-block">
                <div class="left-section">
                    <h2>Welcome to Waste Management System</h2>
                    <p>This is where you can add some introductory text or information about your system.</p>
                </div>
            </div>

            <!-- Right Section -->
            <div class="col-lg-6 p-0">
                <div class="right-section">
                    <div class="signup-form">
                        <h3>Waste Management System</h3>

                        <h2>Sign Up</h2>
                        <?= $message ?>

                        <form action="registration.php" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input type="text" class="form-control" placeholder="First name" name="firstname" >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="text" class="form-control" placeholder="Last name" name="lastname" >
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Email address" name="username" >
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" placeholder="Password" name="password" >
                            </div>
                            <div class="mb-3">
                                <select class="form-select custom-select" name="role" >

                                    <option value="" disabled selected>Select your role</option>
                                    <option value="user">User</option>
                                    <option value="driver">Driver</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Sign Up</button>
                            </div>
                            <p class="form-footer">Already have an account? <a href="index.php">Sign in</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>