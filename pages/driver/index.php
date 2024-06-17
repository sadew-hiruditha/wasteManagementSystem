<?php
session_start();
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== 'driver') {
//    header("Location: ../index.php");
        header("Location: ../index.php?status=unauthorized");

    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard</title>
</head>
<body>
            <h1>Welcome to the Driver Dashboard <?=$_SESSION["user_firstname"]." ".$_SESSION["user_lastname"]?> </h1>


</body>
</html>
