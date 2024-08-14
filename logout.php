<?php
session_start();

unset($_SESSION["user_id"]);
unset($_SESSION["user_firstname"]);
unset($_SESSION["user_lastname"]);
unset($_SESSION["user_role"]);

session_destroy();
header("Location: mainLogin.php");
