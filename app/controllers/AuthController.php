<?php
require_once __DIR__ . '/../../config/Bootstrap.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin($error = '') {
        include __DIR__ . '/../views/login.php';
    }

    public function showRegister($error = '') {
        include __DIR__ . '/../views/register.php';
    }

    public function register() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            if (empty($username) || empty($email) || empty($password)) {
                $this->showRegister("All fields are required.");
                return;
            }

            if ($this->userModel->register($username, $email, $password)) {
                header("Location: index.php?page=login");
                exit();
            } else {
                $this->showRegister("Registration failed. Please try again.");
            }
        }
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            if (empty($username) || empty($password)) {
                $this->showLogin("All fields are required.");
                return;
            }

            $userId = $this->userModel->login($username, $password);
            if ($userId) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $userId;
                header("Location: index.php?page=dashboard");
                exit();
            } else {
                $this->showLogin("Login failed. Please check your credentials.");
            }
        }
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: index.php?page=login");
        exit();
    }
}
?>