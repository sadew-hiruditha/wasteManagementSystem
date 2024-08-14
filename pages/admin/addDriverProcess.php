<?php
require_once '../../classes/User.php';
require_once '../../classes/DbConnector.php';
use classes\User;
use classes\DbConnector;

session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}

if (isset($_POST["firstname"], $_POST["lastname"], $_POST["username"], $_POST["password"], $_POST["confirm_password"], $_POST["mobile"], $_POST["street"], $_POST["city"], $_POST["state"], $_POST["postalcode"])) {
    if (empty($_POST["firstname"]) || empty($_POST["lastname"]) || empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["confirm_password"]) || empty($_POST["mobile"]) || empty($_POST["street"]) || empty($_POST["city"]) || empty($_POST["state"]) || empty($_POST["postalcode"])) {
        $location = "addDriver.php?status=1"; // Empty fields
    } elseif ($_POST["password"] !== $_POST["confirm_password"]) {
        $location = "addDriver.php?status=4"; // Password mismatch
    } elseif (!ctype_digit($_POST["mobile"]) || strlen($_POST["mobile"]) !== 10) {
        $location = "addDriver.php?status=5"; // Invalid mobile number
    } elseif (!ctype_digit($_POST["postalcode"]) || strlen($_POST["postalcode"]) !== 5) {
        $location = "addDriver.php?status=6"; // Invalid postal code
    } elseif (!preg_match('/^(?=.*[!@#$%^&*]).{8,}$/', $_POST["password"])) {
        $location = "addDriver.php?status=8"; // Invalid password format
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
        $role = 'driver'; // Set role as driver

        $db = DbConnector::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bindParam(1, $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        if (!empty($result)) {
            $location = "addDriver.php?status=7"; // Username already exists
        } else {
            $user = new User($first_name, $last_name, $username, $password, $mobile, $street, $city, $state, $postalcode, $role);
            if ($user->register($db)) {
                $location = "addDriver.php?status=2"; // Registration successful
            } else {
                $location = "addDriver.php?status=3"; // Registration failed
            }
        }
    }
} else {
    $location = "addDriver.php?status=0"; // Invalid form submission
}

header("Location: " . $location);
?>