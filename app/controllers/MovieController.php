<?php
require_once __DIR__ . '/../../config/Bootstrap.php';
require_once __DIR__ . '/../models/Movie.php';

class MovieController {
    private $movieModel;

    public function __construct() {
        $this->movieModel = new Movie();
    }

    public function showDashboard() {
        $userId = $_SESSION['user_id'];
        $favorites = $this->movieModel->getFavorites($userId);

        include __DIR__ . '/../views/dashboard.php';
    }

    public function searchMovies() {
        if (!isset($_GET['query'])) {
            echo json_encode(['error' => 'No search query provided']);
            exit();
        }

        $query = urlencode($_GET['query']);
        $apiKey = getenv('OMDB_API_KEY') ?: parse_ini_file(__DIR__ . '/../../.env')['OMDB_API_KEY'];
        $apiUrl = "http://www.omdbapi.com/?apikey={$apiKey}&s={$query}";

        $response = file_get_contents($apiUrl);
        echo $response;
        exit();
    }

    public function addFavorite() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'User not logged in']);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $movieId = $_POST['movie_id'];
            $movieTitle = $_POST['movie_title'];
            $posterUrl = $_POST['poster_url'];

            if ($this->movieModel->addFavorite($userId, $movieId, $movieTitle, $posterUrl)) {
                echo json_encode(['success' => 'Movie added to favorites']);
            } else {
                echo json_encode(['error' => 'Failed to add movie']);
            }
            exit();
        }
    }

    public function removeFavorite() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'User not logged in']);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $movieId = $_POST['movie_id'];

            if ($this->movieModel->removeFavorite($userId, $movieId)) {
                echo json_encode(['success' => 'Movie removed from favorites']);
            } else {
                echo json_encode(['error' => 'Failed to remove movie']);
            }
            exit();
        }
    }

    public function getFavorites() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'User not logged in']);
            exit();
        }

        $userId = $_SESSION['user_id'];
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 8;

        $favorites = $this->movieModel->getFavoritesPaginated($userId, $page, $itemsPerPage);
        echo json_encode($favorites);
        exit();
    }
}