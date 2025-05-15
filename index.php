<?php
define('BASEPATH', true);

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
        default:
            http_response_code(404);
            echo "Unknown action: $action";
    }
} else {
    http_response_code(404);
    echo "Unsupported controller: $controller";
}
