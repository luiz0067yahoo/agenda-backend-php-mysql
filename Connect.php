<?php
class Connect {
    private static $instance;

    private static $servername = "localhost";
    private static $username   = "root";
    private static $password   = "";
    private static $database   = "agenda";

    private function __construct() {}

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new PDO(
                "mysql:host=" . self::$servername . ";dbname=" . self::$database . ";charset=utf8mb4",
                self::$username,
                self::$password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }
        return self::$instance;
    }

    public static function close() {
        self::$instance = null;
    }
}