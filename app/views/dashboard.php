<?php
require_once __DIR__ . '/../../config/Bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit();
}

$userId = $_SESSION['user_id'];
require_once __DIR__ . '/../models/User.php';
$userModel = new User();
$username = $userModel->getUsernameById($userId);

require_once __DIR__ . '/../models/Movie.php';
$movieModel = new Movie();
$favorites = $movieModel->getFavorites($userId);
$favoriteMovieIds = array_column($favorites, 'movie_id');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Movie App</title>
    <link rel="stylesheet" href="public/css/style.css">
    <script>
        const favoriteMovieIds = <?= json_encode($favoriteMovieIds) ?>;
        let currentPage = 1;
        let currentFavoritesPage = 1;
        const itemsPerPage = 8;

        function searchMovies() {
            currentPage = 1; // Reset pagination
            const query = document.getElementById('searchQuery').value;
            fetch(`index.php?page=search&query=${query}`)
                .then(response => response.json())
                .then(data => {
                    let results = document.getElementById('searchResults');
                    results.innerHTML = "";
                    if (data.Search) {
                        const paginatedData = paginate(data.Search, currentPage, itemsPerPage);
                        paginatedData.forEach(movie => {
                            const isFavorite = favoriteMovieIds.includes(movie.imdbID);
                            results.innerHTML += `
                                <div class="movie-item">
                                    <img src="${movie.Poster}" width="100">
                                    <p>${movie.Title}</p>
                                    <button class="btn btn-primary" onclick="addFavorite('${movie.imdbID}', '${movie.Title}', '${movie.Poster}')" ${isFavorite ? 'disabled' : ''}>${isFavorite ? 'Added to Favorites' : 'Add to Favorites'}</button>
                                </div>`;
                        });
                        renderPagination(data.Search.length, 'searchResultsPagination');
                    } else {
                        results.innerHTML = "<p>No results found.</p>";
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function addFavorite(movieId, title, poster) {
            fetch('index.php?page=add_favorite', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `movie_id=${movieId}&movie_title=${title}&poster_url=${poster}`
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      favoriteMovieIds.push(movieId);
                      const addButton = document.querySelector(`button[onclick="addFavorite('${movieId}', '${title}', '${poster}')"]`);
                      if (addButton) {
                          addButton.disabled = true;
                          addButton.innerText = 'Added to Favorites';
                      }
                      updateFavoritesList();
                  } else {
                      alert(data.error);
                  }
              })
              .catch(error => console.error('Error:', error));
        }

        function removeFavorite(movieId) {
            fetch('index.php?page=remove_favorite', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `movie_id=${movieId}`
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      const index = favoriteMovieIds.indexOf(movieId);
                      if (index > -1) {
                          favoriteMovieIds.splice(index, 1);
                      }
                    //   const addButton = document.querySelector(`button[onclick="addFavorite('${movieId}', '${title}', '${poster}')"]`);
                    //   if (addButton) {
                    //       addButton.disabled = false;
                    //       addButton.innerText = 'Add to Favorites';
                    //   }
                      updateFavoritesList();
                  } else {
                      alert(data.error);
                  }
              })
              .catch(error => console.error('Error:', error));
        }

        function updateFavoritesList() {
            // fetch(`index.php?page=get_favorites&page=${currentFavoritesPage}&itemsPerPage=${itemsPerPage}`)
            //     .then(response => response.json())
            //     .then(data => {
            //         console.log(response);
            //         const favoritesList = document.getElementById('favoritesList');
            //         favoritesList.innerHTML = "";
            //         const paginatedData = paginate(data, currentFavoritesPage, itemsPerPage);
            //         paginatedData.forEach(fav => {
            //             favoritesList.innerHTML += `
            //                 <div class="movie-item" id="fav-${fav.movie_id}">
            //                     <img src="${fav.poster_url}" width="100">
            //                     <p>${fav.movie_title}</p>
            //                     <button class="btn btn-secondary" onclick="removeFavorite('${fav.movie_id}')">Remove</button>
            //                 </div>`;
            //         });
            //         renderPagination(data.length, 'favoritesListPagination', 'favorites');
            //     })
            //     .catch(error => console.error('Error:', error));
        }

        function paginate(items, page, perPage) {
            const offset = (page - 1) * perPage;
            return items.slice(offset, offset + perPage);
        }

        function renderPagination(totalItems, paginationId, type = 'search') {
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const pagination = document.getElementById(paginationId);
            pagination.innerHTML = "";
            for (let i = 1; i <= totalPages; i++) {
                pagination.innerHTML += `<button class="btn btn-secondary" onclick="changePage(${i}, '${paginationId}', '${type}')">${i}</button>`;
            }
        }

        function changePage(page, paginationId, type = 'search') {
            if (type === 'search') {
                currentPage = page;
                searchMovies();
            } else {
                currentFavoritesPage = page;
                updateFavoritesList();
            }
        }

        // document.addEventListener('DOMContentLoaded', () => {
        //     updateFavoritesList();
        // });
    </script>
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="dashboard-container">
        <h2>Welcome, <?= htmlspecialchars($username) ?></h2>

        <h3>Search Movies</h3>
        <input type="text" id="searchQuery" placeholder="Search for movies...">
        <button class="btn btn-primary" onclick="searchMovies()">Search</button>
        <div id="searchResults" class="movie-list"></div>
        <div id="searchResultsPagination" class="pagination"></div>

        <h3>Your Favorite Movies</h3>
        <div id="favoritesList" class="movie-list">
            <?php foreach ($favorites as $fav) : ?>
                <div class="movie-item" id="fav-<?= $fav['movie_id'] ?>">
                    <img src="<?= $fav['poster_url'] ?>" width="100">
                    <p><?= htmlspecialchars($fav['movie_title']) ?></p>
                    <button class="btn btn-secondary" onclick="removeFavorite('<?= $fav['movie_id'] ?>')">Remove</button>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="favoritesListPagination" class="pagination"></div>
    </div>
</body>
</html>