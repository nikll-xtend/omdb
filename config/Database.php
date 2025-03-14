<?php
class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $env = parse_ini_file(__DIR__ . '/../.env'); 

        $host = $env['DB_HOST'];
        $dbname = $env['DB_NAME'];
        $user = $env['DB_USER'];
        $pass = $env['DB_PASS'];

        $this->conn = new mysqli($host, $user, $pass, $dbname);
        
        if ($this->conn->connect_error) {
            die("Database Connection Failed: " . $this->conn->connect_error);
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
?>
