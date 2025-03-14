<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie App</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="container">
        <h1> Welcome to Movie App</h1>
        <p>Find and save your favorite movies with ease.</p>
        <a href="?page=login" class="btn btn-primary">Login</a>
        <a href="?page=register" class="btn btn-secondary">Register</a>
    </div>
    <?php include 'partials/footer.php'; ?>
</body>
</html>