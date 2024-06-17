<?php

require_once './classes/User.php';
require_once './classes/DbConnector.php';

use classes\User;
use classes\DbConnector;

if (isset($_POST["firstname"], $_POST["lastname"], $_POST["username"], $_POST["password"],$_POST["role"])) {
    if (empty($_POST["firstname"]) || empty($_POST["lastname"]) || empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["role"])) {

        $location = "sign_up.php?status=1";
    } else {
        $first_name = $_POST["firstname"];
        $last_name = $_POST["lastname"];
        $username = $_POST["username"];
        $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
        $role = $_POST["role"];
        $user = new User($first_name, $last_name, $username, $password, $role);
        if($user->register(DbConnector::getConnection())){
            $location = "sign_up.php?status=2";
        }else{
            $location = "sign_up.php?status=3";
        }
    }
} else {
    $location = "sign_up.php?status=0";
}

header("Location:" . $location);
