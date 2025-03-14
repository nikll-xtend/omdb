<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Movie App</title>
    <link rel="stylesheet" href="public/css/style.css">
    <script src="public/assets/validation.js" defer></script>
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="container">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error-message" style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form id="loginForm" action="index.php?page=login" method="POST">
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="index.php?page=register" style="color:#f5a623;">Register</a></p>
    </div>
    <?php include 'partials/footer.php'; ?>
</body>
</html>