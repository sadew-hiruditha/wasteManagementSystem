<?php
require_once './classes/User.php';
require_once './classes/DbConnector.php';
use classes\User;
use classes\DbConnector;

if (isset($_POST["firstname"], $_POST["lastname"], $_POST["username"], $_POST["password"], $_POST["confirm_password"], $_POST["mobile"], $_POST["street"], $_POST["city"], $_POST["state"], $_POST["postalcode"])) {
    if (empty($_POST["firstname"]) || empty($_POST["lastname"]) || empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["confirm_password"]) || empty($_POST["mobile"]) || empty($_POST["street"]) || empty($_POST["city"]) || empty($_POST["state"]) || empty($_POST["postalcode"])) {
        $location = "sign_up.php?status=1";
    } elseif ($_POST["password"] !== $_POST["confirm_password"]) {
        $location = "sign_up.php?status=4"; // New status for password mismatch
    } else {
        $first_name = $_POST["firstname"];
        $last_name = $_POST["lastname"];
        $username = $_POST["username"];
        $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
        $mobile = $_POST["mobile"];
        $street = $_POST["street"];
        $city = $_POST["city"];
        $state = $_POST["state"];
        $postalcode = $_POST["postalcode"];
        // Default role is 'user'
        $role = 'user';

        $user = new User($first_name, $last_name, $username, $password, $mobile, $street, $city, $state, $postalcode, $role);
        if($user->register(DbConnector::getConnection())){
            $location = "sign_up.php?status=2";
        } else {
            $location = "sign_up.php?status=3";
        }
    }
} else {
    $location = "sign_up.php?status=0";
}
header("Location:" . $location);