<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once __DIR__ . '/../vendor/autoload.php'; 
require_once __DIR__ . '/../app/config.php';
// === Conexión PDO global para los controladores ===
try {
    // Si en app/config.php tienes constantes, úsalo así:
    // $host = DB_HOST; $db = DB_NAME; $user = DB_USER; $pass = DB_PASS;

    // O define aquí explícito:
    $host = '127.0.0.1';      // o 'localhost'
    $db   = 'qpay';           // tu BD (según phpMyAdmin de tus capturas)
    $user = 'root';           // usuario local
    $pass = '';               // contraseña local (XAMPP/LAMPP suele ser vacía)

    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    die('Error de conexión a la base de datos: ' . $e->getMessage());
}

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
    // Instanciar pasando $pdo solo si el constructor lo requiere
    $ref  = new ReflectionClass($controllerName);
    $ctor = $ref->getConstructor();

    if ($ctor && $ctor->getNumberOfParameters() >= 1) {
        $controllerObject = $ref->newInstanceArgs([$pdo]);
    } else {
        $controllerObject = $ref->newInstance();
    }

    if (method_exists($controllerObject, $method)) {
        call_user_func_array([$controllerObject, $method], $params);
    } else {
        http_response_code(500);
        echo "Método {$method} no encontrado en $controllerName";
    }
} else {
    http_response_code(404);
    echo "Página no encontrada";
}
