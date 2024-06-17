<?php

namespace classes;

use PDO;
use PDOException;

Class DbConnector {

    private static $host = "localhost";
    private static $dbname = "wasatemanagementsystem";
    private static $dbuser = "root";
    private static $dbpass = "";

    public static function getConnection() {

        try {
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname;
            $con = new PDO($dsn, self::$dbuser, self::$dbpass);

            return $con;
        } catch (PDOException $ex) {
            die("Error in database connetion" . $ex->getMessage());
        }
    }
}
