<?php
define('BASEPATH', true);


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
    }

}



// Routing example
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
            http_response_code(404);
            echo "Unknown action: $action";
    }
} else {
    http_response_code(404);
    echo "Unsupported controller: $controller";
}
