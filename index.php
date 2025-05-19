<?php
session_start();
define('BASEPATH', true);

if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
}

// Get the request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'GET') {
    // Get the full URI
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Remove the part before index.php (if any)
    $base = '/index.php';
    if (strpos($uri, $base) === 0) {
        $uri = substr($uri, strlen($base));
    }

    // Clean up
    $uri = trim($uri, '/'); // e.g., 'news/show/5'

    // Split into segments
    $segments = explode('/', $uri);

    // Defaults
    $controller = $segments[0] ?? 'news';
    $action     = $segments[1] ?? 'list';
    $param      = $segments[2] ?? null;

} elseif ($requestMethod === 'POST') {
    if ($_POST['form_action'] === 'handle_news_form') {
        $controller = 'news';
        $action = 'handle_news_form';
    } elseif ($_POST['form_action'] === 'delete_news') {
        $controller = 'news';
        $action = 'delete_news';
    } elseif ($_POST['form_action'] === 'handle_login') {
        $controller = 'users';
        $action = 'handle_login';
    }
}

// Routing
if ($controller === 'news') {
    require_once 'controller/NewsController.php';
    $newsController = new NewsController();

    switch ($action) {
        case 'list':
            $newsController->list();
            break;
        case 'show':
            $newsController->show($param);
            break;
        case 'show_news_form':
            $newsController->showForm($param);
            break;
        case 'handle_news_form':
            $newsController->handleNewsForm();
            break;
        case 'delete_news':
            $newsController->deleteNews();
            break;
        default:
            header("Location: /index.php/news/list");
            exit;
    }

} elseif ($controller === 'users') {
    require_once 'controller/UsersController.php';
    $usersController = new UsersController();

    switch ($action) {
        case 'login':
            $usersController->showLoginForm();
            break;
        case 'handle_login':
            $usersController->handleLogin();
            break;
        case 'logout':
            $usersController->logout();
            break;
        default:
            header("Location: /index.php/news/list");
            exit;
    }

} else {
    header("Location: /index.php/news/list");
    exit;
}
