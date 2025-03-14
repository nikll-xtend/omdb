<?php
class Database
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $env = parse_ini_file(__DIR__ . '/../.env');

        $host = getenv('DB_HOST') ?: (file_exists($env) ? parse_ini_file($env)['DB_HOST'] : '');
        $user = getenv('DB_USER') ?: (file_exists($env) ? parse_ini_file($env)['DB_USER'] : '');
        $pass = getenv('DB_PASS') ?: (file_exists($env) ? parse_ini_file($env)['DB_PASS'] : '');
        $dbname = getenv('DB_NAME') ?: (file_exists($env) ? parse_ini_file($env)['DB_NAME'] : '');


        $this->conn = new mysqli($host, $user, $pass, $dbname);

        if ($this->conn->connect_error) {
            die("Database Connection Failed: " . $this->conn->connect_error);
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
