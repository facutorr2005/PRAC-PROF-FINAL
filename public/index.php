<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once __DIR__ . '/../app/config.php';
spl_autoload_register(function ($class) {
    $class = str_replace('App\\', '', $class);
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    require_once __DIR__ . '/../app/' . $class . '.php';
});

$uri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$basePath = str_replace('\\', '/', $scriptName);
$uri = str_replace($basePath, '', $uri);
$uri = strtok($uri, '?');
$uriParts = explode('/', trim($uri, '/'));

$controller = $uriParts[0] ?? '';
$method = $uriParts[1] ?? 'index';
$params = array_slice($uriParts, 2);

// Si entro a la raíz sin controlador, voy al login
if ($controller === '' || $controller === null) {
    header('Location: ' . BASE_URL . '/Usuarios/iniciarSesion');
    exit;
}


$controllerName = 'App\\Controllers\\' . ucfirst($controller) . 'Controller';

if (class_exists($controllerName)) {
    $controllerObject = new $controllerName();
    if (method_exists($controllerObject, $method)) {
        call_user_func_array([$controllerObject, $method], $params);
    } else {
        http_response_code(500);
        echo "Método '{$method}' no encontrado en $controllerName";
    }
} else {
    http_response_code(404);
    echo "Página no encontrada";
}
