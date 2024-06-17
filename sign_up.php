<?php
$message = null;
if(isset($_GET["status"])){
    $status = $_GET["status"];
    
    if($status == 0){
        $message = "<h6>Required values were not submited</h6>";
        
    }elseif($status==1){
                $message = "<h6>You must fill all filed register with Samadhi bookstore</h6>";

    }elseif($status==2){
                $message = "<h6>You are successfully registered with Samadhi BookStore</h6>";

    }elseif($status==3){
                $message = "<h6>Error occurred during the registration please try again</h6>";

    }
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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

.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 400px;
}

.signup-form {
    text-align: center;
}

.signup-form h2 {
    margin-bottom: 20px;
}

.signup-form .input-group {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 15px;
}

.signup-form input[type="text"],
.signup-form input[type="email"],
.signup-form input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.signup-form .button {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.signup-form button:hover {
    background-color: #0056b3;
}

.signup-form p {
    margin-top: 20px;
}

.signup-form p a {
    color: #007bff;
    text-decoration: none;
}

.signup-form p a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="signup-form">
            <h2>Sign Up</h2>
            <?=$message?>
            
            <form action="registration.php" method="POST">
                <div class="input-group">
                    <input type="text" placeholder="First name" name="firstname" >
                    <input type="text" placeholder="Last name" name="lastname" >
                </div>
                <input type="email" placeholder="Email address" name="username" >
                <input type="password" placeholder="Password" name="password" >
                 <select name="role" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                    <option value="driver">Driver</option>
                </select>
                <input class="button" type="submit">Sign Up
                <p>Already have an account? <a href="index.php">Sign in</a></p>
            </form>
        </div>
    </div>
</body>
</html>
