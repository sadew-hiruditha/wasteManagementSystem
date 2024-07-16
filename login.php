<?php

session_start();

require_once './classes/DbConnector.php'; 
require_once './classes/User.php';

use classes\User;
use classes\DbConnector;

if (isset($_POST["username"], $_POST["password"])) {
    if (empty($_POST["username"]) || empty($_POST["password"])) {
        $location = "index.php?status=1"; 
    } else {
        $username = $_POST["username"];
        $password = $_POST["password"];
//        $firstname = $_POST["firstname"];
//        $lastname = $_POST["lastname"];
        

        $user = new User(null, null, $username, $password, null);

       
        if ($user->authenticate(DbConnector::getConnection())) {
            // Check if selected role matches user's actual role
//            if ($user->getRole() !== $role) {
//                $location = "index.php?status=2"; // Role mismatch
//            } else {
            // Authentication successful, set session variables
            $_SESSION["user_id"] = $user->getId();
            $_SESSION["user_name"] = $user->getUsername();
            $_SESSION["user_firstname"] = $user->getFirst_name();
            $_SESSION["user_lastname"] = $user->getLast_name();
            $_SESSION["user_role"] = $user->getRole();
            
             if (isset($_POST["rememberMe"])) {
                setcookie("username", $username, time() + (86400 * 30), "/"); // 30 days
                setcookie("fname", $user->getFirst_name(), time() + (86400 * 30), "/"); // 30 days
                setcookie("lname", $user->getLast_name(), time() + (86400 * 30), "/"); // 30 days
            }

            // Redirect to appropriate dashboard based on role
            switch ($user->getRole()) {
                case 'admin':
                    $location = "pages/admin/"; // Redirect to admin dashboard
                    break;
                case 'user':
                    $location = "pages/user/"; // Redirect to user dashboard
                    break;
                case 'driver':
                    $location = "pages/driver/"; // Redirect to driver dashboard
                    break;
                default:
                    $location = "index.php?status=2"; // Invalid role
            }
        } else {
            $location = "index.php?status=2"; // Failed authentication
        }
    }
} else {
    $location = "index.php?status=0"; // Required fields not submitted
}

header("Location: $location"); // Redirect user to appropriate page
