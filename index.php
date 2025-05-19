<?php
session_start(); // Start the session to manage user login state
define('BASEPATH', true); // Define a constant to prevent direct script access

// Store the user ID if the user is logged in
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
}

// Get the request method (GET or POST)
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'GET') {
    // Get the full URI path (e.g., /index.php/news/show/5)
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Remove the /index.php prefix if present
    $base = '/index.php';
    if (strpos($uri, $base) === 0) {
        $uri = substr($uri, strlen($base));
    }

    // Trim leading/trailing slashes
    $uri = trim($uri, '/');

    // Split the URI into parts: controller/action/param
    $segments = explode('/', $uri);

    // Set default controller, action, and optional parameter
    $controller = $segments[0] ?? 'news';
    $action     = $segments[1] ?? 'list';
    $param      = $segments[2] ?? null;

} elseif ($requestMethod === 'POST') {
    // Set controller and action based on form_action POST parameter
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

// Routing logic for the "news" controller
if ($controller === 'news') {
    require_once 'controller/NewsController.php'; // Load NewsController
    $newsController = new NewsController();       // Create controller instance

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
            // Redirect to news list if action is unknown
            header("Location: /index.php/news/list");
            exit;
    }

// Routing logic for the "users" controller
} elseif ($controller === 'users') {
    require_once 'controller/UsersController.php'; // Load UsersController
    $usersController = new UsersController();      // Create controller instance

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
            // Redirect to news list if action is unknown
            header("Location: /index.php/news/list");
            exit;
    }

// Fallback: redirect to news list if controller is not recognized
} else {
    header("Location: /index.php/news/list");
    exit;
}
