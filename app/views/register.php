<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Movie App</title>
    <link rel="stylesheet" href="public/css/style.css">
    <script src="public/assets/validation.js" defer></script>
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <div class="container">
        <h2>Create an Account</h2>
        <form id="registerForm" action="index.php?page=register" method="POST">
            <input type="text" name="username" placeholder="Enter your username" required>
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Create a password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="?page=login" style="color:#f5a623;">Login</a></p>
    </div>
    <?php include 'partials/footer.php'; ?>
</body>
</html>