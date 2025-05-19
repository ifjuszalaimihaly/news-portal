<?php
$currentUri = $_SERVER['REQUEST_URI'];
$isLoggedIn = isset($_SESSION['user']);
?>

<nav>
    <ul>
        <!-- News list link (unless already there) -->
        <?php if (strpos($currentUri, '/news/list') === false): ?>
            <li><a href="/index.php/news/list">News List</a></li>
        <?php endif; ?>

        <!-- Show create form if logged in and not already there -->
        <?php if ($isLoggedIn && strpos($currentUri, '/news/show_news_form') === false): ?>
            <li><a href="/index.php/news/show_news_form">Create News</a></li>
        <?php endif; ?>

        <!-- Login or Logout -->
        <?php if ($isLoggedIn): ?>
            <li><a href="/index.php/users/logout">Logout (<?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?>)</a></li>
        <?php else: ?>
            <li><a href="/index.php/users/login">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
