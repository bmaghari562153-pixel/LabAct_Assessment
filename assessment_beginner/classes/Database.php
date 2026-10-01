<?php
// Singleton database connection (XAMPP defaults - change if yours differ)
class Database
{
    private static ?mysqli $conn = null;

    private const HOST = "localhost";
    private const USER = "root";
    private const PASS = "";
    private const NAME = "assessment_db";

    private function __construct() {}

    public static function connect(): mysqli
    {
        if (self::$conn === null) {
            self::$conn = new mysqli(self::HOST, self::USER, self::PASS, self::NAME);
            if (self::$conn->connect_error) {
                die("Database connection failed: " . self::$conn->connect_error);
            }
            self::$conn->set_charset("utf8mb4");
        }
        return self::$conn;
    }
}
