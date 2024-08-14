<?php
session_start();
require_once './classes/DbConnector.php'; 
require_once './classes/User.php';
use classes\User;
use classes\DbConnector;

if (isset($_POST["username"], $_POST["password"])) {
    if (empty($_POST["username"]) || empty($_POST["password"])) {
        $location = "mainLogin.php?status=1"; 
    } else {
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        $user = new User(null, null, $username, $password, null, null, null, null, null, null);
        
        try {
            if ($user->authenticate(DbConnector::getConnection())) {
                $_SESSION["user_id"] = $user->getId();
                $_SESSION["user_username"] = $user->getUsername();
                $_SESSION["user_firstname"] = $user->getFirst_name();
                $_SESSION["user_lastname"] = $user->getLast_name();
                $_SESSION["user_mobile"] = $user->getMobile();
                $_SESSION["user_street"] = $user->getStreet();
                $_SESSION["user_city"] = $user->getCity();
                $_SESSION["user_state"] = $user->getState();
                $_SESSION["user_postalcode"] = $user->getPostalcode();
                $_SESSION["user_role"] = $user->getRole();
                
                if (isset($_POST["rememberMe"])) {
                    setcookie("username", $username, time() + (86400 * 30), "/");
                    setcookie("fname", $user->getFirst_name(), time() + (86400 * 30), "/");
                    setcookie("lname", $user->getLast_name(), time() + (86400 * 30), "/");
                }
                
                // Redirect based on role
                if ($user->getRole() == "admin") {
                    $location = "pages/admin/adminDashboard.php";
                } elseif ($user->getRole() == "driver") {
                    $location = "pages/driver/driverDashboard.php";
                } else {
                    $location = "pages/user/userDashboard.php";
                }
            } else {
                $location = "mainLogin.php?status=2";
            }
        } catch (Exception $e) {
            // Log the error
            error_log("Authentication error: " . $e->getMessage());
            $location = "mainLogin.php?status=2";
        }
    }
} else {
    $location = "mainLogin.php?status=0";
}

header("Location: $location");
exit;
?>