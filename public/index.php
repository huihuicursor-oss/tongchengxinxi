<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/extend/bootstrap.php';

$config = include dirname(__DIR__) . '/application/config.php';
$routes = include dirname(__DIR__) . '/application/route.php';

$path = isset($_GET['s']) ? trim((string) $_GET['s']) : '/' . $config['default_route'];
if ($path === '') {
    $path = '/' . $config['default_route'];
}
if ($path[0] !== '/') {
    $path = '/' . $path;
}

if (!isset($routes[$path])) {
    http_response_code(404);
    echo '404 Not Found';
    exit;
}

$route = $routes[$path];
$controllerClass = 'app\\admin\\controller\\' . $route['controller'];
$action = $route['action'];

if (!class_exists($controllerClass)) {
    http_response_code(500);
    echo 'Controller Not Found: ' . htmlspecialchars($controllerClass, ENT_QUOTES, 'UTF-8');
    exit;
}

$controller = new $controllerClass();

if (!method_exists($controller, $action)) {
    http_response_code(500);
    echo 'Action Not Found: ' . htmlspecialchars($action, ENT_QUOTES, 'UTF-8');
    exit;
}

echo $controller->$action();
