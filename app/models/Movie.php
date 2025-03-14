<?php
require_once __DIR__ . '/../../config/Database.php';

class Movie {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    public function getFavorites($userId) {
        $stmt = $this->conn->prepare("SELECT movie_id, movie_title, poster_url FROM favorite_movies WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getFavoritesPaginated($userId, $page, $itemsPerPage) {
        $offset = ($page - 1) * $itemsPerPage;
        $stmt = $this->conn->prepare("SELECT movie_id, movie_title, poster_url FROM favorite_movies WHERE user_id = ? LIMIT ? OFFSET ?");
        $stmt->bind_param("iii", $userId, $itemsPerPage, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addFavorite($userId, $movieId, $movieTitle, $posterUrl) {
        $stmt = $this->conn->prepare("INSERT INTO favorite_movies (user_id, movie_id, movie_title, poster_url) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $movieId, $movieTitle, $posterUrl);
        return $stmt->execute();
    }

    public function removeFavorite($userId, $movieId) {
        $stmt = $this->conn->prepare("DELETE FROM favorite_movies WHERE user_id = ? AND movie_id = ?");
        $stmt->bind_param("is", $userId, $movieId);
        return $stmt->execute();
    }
}