<?php

session_start();

require_once './classes/User.php';
require_once './classes/DbConnector.php'; // Corrected the path here

use classes\User;
use classes\DbConnector;

if (isset($_POST["username"], $_POST["password"])) {
    if (empty($_POST["username"]) || empty($_POST["password"])) {
        $location = "index.php?status=1";
    } else {
        $username = $_POST["username"];
        $password = $_POST["password"];


        $user = new User(null, null, $username, $password, null);

        if ($user->authenticate(DbConnector::getConnection())) {
            $_SESSION["user_id"] = $user->getId();
            $_SESSION["user_firstname"] = $user->getFirst_name();
            $_SESSION["user_lastname"] = $user->getLast_name();
            $_SESSION["user_role"] = $user->getRole();
//        $location = "user/";

            switch ($user->getRole()) {
                case 'admin':
                    $location = "pages/admin/";
                    break;
                case 'user':
                    $location = "pages/user/";
                    break;
                case 'driver':
                    $location = "pages/driver/";
                    break;
                default :
                    $location = "index.php?status=2";
            }
        } else {
            $location = "index.php?status=2"; // Failed authentication
        }
    }
} else {
    $location = "index.php?status=0";
}

header("Location:" . $location); // Corrected the typo here