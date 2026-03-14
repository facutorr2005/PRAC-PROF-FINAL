<?php
declare(strict_types=1);

date_default_timezone_set('America/Argentina/Buenos_Aires');

// --- CONFIGURACIÓN DINÁMICA (Local vs Host) ---
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['REMOTE_ADDR'] === '127.0.0.1') {
    // Entorno LOCAL (XAMPP en Linux)
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
    define('BASE_URL', '/PRAC-PROF-FINAL/Public');
    
    define('DB_DSN', 'mysql:host=localhost;dbname=if0_40531076_qpay;charset=utf8mb4');
    define('DB_USER', 'root');
    define('DB_PASS', ''); 
} else {
    // Entorno HOST (InfinityFree)
    ini_set('display_errors', '0');
    error_reporting(0);
    define('BASE_URL', ''); // En InfinityFree suele ser vacío si apunta a Public
    
    define('DB_DSN', 'mysql:host=sql100.infinityfree.com;dbname=if0_40531076_qpay;charset=utf8mb4');
    define('DB_USER', 'if0_40531076');
    define('DB_PASS', 'qpayproyecto');
}

// --- FUNCIONES Y RUTAS (No tocar) ---
function url(string $path = '/'): string {
  $path = '/' . ltrim($path, '/');
  return rtrim(BASE_URL, '/') . $path;
}

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

define('APP_PATH',  dirname(__DIR__) . '/App');
define('VIEW_PATH', APP_PATH . '/Views');

// --- EMAIL (Brevo SMTP) ---
define('MAIL_HOST', 'smtp-relay.brevo.com');
define('MAIL_PORT', 587);
define('MAIL_USER', '9854d6001@smtp-brevo.com');
define('MAIL_PASS', 'I7mqwD1n9AVCJWg6');
define('MAIL_FROM', 'proyectoqpay@gmail.com');
define('MAIL_FROM_NAME', 'Q-Pay');