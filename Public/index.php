<?php
declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';


require_once __DIR__ . '/../Config/config.php';

// Cargas (ajustá nombres según tu estructura real)
require_once __DIR__ . '/../App/Core/router.php';
require_once __DIR__ . '/../App/Controllers/UsuariosController.php';
require_once __DIR__ . '/../App/Model/UsuarioModel.php'; // si tu carpeta es "Model" (singular)
require_once __DIR__ . '/../App/Controllers/ComprasController.php';
require_once __DIR__ . '/../App/Model/ProductoModel.php';
require_once __DIR__ . '/../App/Model/CompraModel.php';


use App\Core\Router;
use App\Controllers\UsuariosController;
use App\Controllers\ComprasController;

$router = new Router();

// Rutas mínimas para login
$router->get('/',       [UsuariosController::class, 'loginForm']);
$router->get('/login',  [UsuariosController::class, 'loginForm']);
$router->post('/login', [UsuariosController::class, 'login']);
$router->get('/panel',  [UsuariosController::class, 'panel']);
$router->get('/perfil', [UsuariosController::class, 'perfil']);
$router->get('/registro', [UsuariosController::class, 'registerForm']);
$router->post('/registro', [UsuariosController::class, 'register']);
$router->get('/recuperacion',  [UsuariosController::class, 'forgotForm']);
$router->post('/recuperacion', [UsuariosController::class, 'forgot']);
$router->get('/mailer-preview', [UsuariosController::class, 'mailerPreview']);
$router->get('/codigo', [UsuariosController::class, 'codeForm']);
$router->post('/codigo', [UsuariosController::class, 'verifyCode']);
$router->get('/reset', [UsuariosController::class, 'resetForm']);
$router->post('/reset', [UsuariosController::class, 'reset']);
$router->get('/logout', [UsuariosController::class, 'logout']);
$router->post('/logout', [UsuariosController::class, 'logout']);
$router->get('/confirmareliminacion', [UsuariosController::class, 'confirmarEliminacion']);
$router->post('/confirmareliminacion', [UsuariosController::class, 'confirmarEliminacion']);


// Rutas de compras

$router->get('/compra', [ComprasController::class, 'compraForm']);
$router->get('/historial', [ComprasController::class, 'historial']);
// API para buscar producto por EAN (AJAX desde JS)
$router->get('/api/producto', [ComprasController::class, 'apiBuscarProducto']);
// API para guardar compra (AJAX desde JS)
$router->post('/api/compra', [ComprasController::class, 'apiGuardarCompra']);

// Nuevas rutas para QR y detalle
$router->get('/compras/qr/{id}', [ComprasController::class, 'qr']);
$router->get('/compras/detalle/{id}', [ComprasController::class, 'detalle']);
// Normalizar path: quitar BASE_URL del REQUEST_URI
$uriFull = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$base    = rtrim(BASE_URL, '/'); // /PRAC-PROF-FINAL/Public
$path    = preg_replace('#^' . preg_quote($base, '#') . '#', '', $uriFull);
$path    = '/' . ltrim($path, '/');

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $path);

// Public/index.php
$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
} // si no existe, seguimos sin Composer (pero Mailer no funcionará)


