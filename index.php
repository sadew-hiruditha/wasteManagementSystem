<?php
$message = null;
if (isset($_GET["status"])) {
    $status = $_GET["status"];

    if ($status == 0) {
        $message = '<div class="alert alert-danger" role="alert"><h6>Required values were not submitted</h6></div>';
    } elseif ($status == 1) {
        $message = '<div class="alert alert-danger" role="alert"><h6>You must fill all fields to login</h6></div>';
    } elseif ($status == 2) {
        $message = '<div class="alert alert-danger" role="alert"><h6>Incorrect username or password</h6></div>';
    } elseif ($status == 'unauthorized') {
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
        <link rel="stylesheet" href="css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }

            .signin-form {
                max-width: 400px;
                margin: auto;
                padding: 30px;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .signin-form h2 {
                margin-bottom: 20px;
                text-align: center;
            }

            .form-control {
                padding: 10px;
                margin-bottom: 15px;
                border-radius: 4px;
            }

            .form-control:focus {
                border-color: #007bff;
                box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
            }

            .btn-primary {
                padding: 10px;
                width: 100%;
                border-radius: 4px;
            }

            .btn-primary:hover {
                background-color: #0056b3;
                border-color: #0056b3;
            }

            .form-footer {
                text-align: center;
                margin-top: 20px;
            }
        </style>

    </head>
    <body>
        <div class="container">
            <div class="signin-form">
                <h2>Sign In Waste</h2>
<?= $message ?>
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <input type="email" class="form-control" placeholder="Email address" name="username" >
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password" >
                    </div>
                    <button type="submit" class="btn btn-primary">Sign in</button>
                    <p class="form-footer">Don't have an account? <a href="sign_up.php">Sign up</a></p>
                </form>
            </div>
        </div>
        <script>
   
</script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
