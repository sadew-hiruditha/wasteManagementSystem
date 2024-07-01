<?php
session_start();

require_once './classes/User.php'; // Adjust path as per your project structure
require_once './classes/DbConnector.php'; // Adjust path as per your project structure

use classes\User;
use classes\DbConnector;

if (isset($_POST["username"], $_POST["password"], $_POST["role"])) {
    if (empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["role"])) {
        $location = "index.php?status=1"; // Missing username, password, or role
    } else {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $role = $_POST["role"];

        $user = new User(null, null, $username, $password, null);

        // Authenticate user
        if ($user->authenticate(DbConnector::getConnection())) {
            // Check if selected role matches user's actual role
            if ($user->getRole() !== $role) {
                $location = "index.php?status=2"; // Role mismatch
            } else {
                // Authentication successful, set session variables
                $_SESSION["user_id"] = $user->getId();
                $_SESSION["user_firstname"] = $user->getFirst_name();
                $_SESSION["user_lastname"] = $user->getLast_name();
                $_SESSION["user_role"] = $user->getRole();

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
            }
        } else {
            $location = "index.php?status=2"; // Failed authentication
        }
    }
} else {
    $location = "index.php?status=0"; // Required fields not submitted
}

header("Location: $location"); // Redirect user to appropriate page
