<?php
require_once __DIR__ . '/config/Bootstrap.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/MovieController.php';

$authController = new AuthController();
$movieController = new MovieController();

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$routes = [
    'home' => fn() => include __DIR__ . '/app/views/home.php',
    'login' => fn() => ($_SERVER['REQUEST_METHOD'] === 'POST') ? $authController->login() : $authController->showLogin(),
    'register' => fn() => ($_SERVER['REQUEST_METHOD'] === 'POST') ? $authController->register() : $authController->showRegister(),
    'logout' => fn() => $authController->logout(),
    'dashboard' => fn() => $movieController->showDashboard(),
    'search' => fn() => $movieController->searchMovies(),
    'add_favorite' => fn() => $movieController->addFavorite(),
    'remove_favorite' => fn() => $movieController->removeFavorite(),
];

if (array_key_exists($page, $routes)) {
    $routes[$page]();
} else {
    include __DIR__ . '/app/views/home.php';
}
?>