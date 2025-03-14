<header class="header">
    <div class="logo"> Movie App</div>
    <nav class="navbar">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="index.php?page=dashboard">Dashboard</a>
            <a href="index.php?page=logout">Logout</a>
        <?php else: ?>
            <a href="index.php">Home</a>
            <a href="index.php?page=login">Login</a>
            <a href="index.php?page=register">Register</a>
        <?php endif; ?>
    </nav>
</header>