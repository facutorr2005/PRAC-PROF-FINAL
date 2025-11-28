<?php
declare(strict_types=1);

date_default_timezone_set('America/Argentina/Buenos_Aires');


// Errores (dev)
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Base del sitio (ajustar si cambiamos la carpeta)
define('BASE_URL', '/PRAC-PROF-FINAL/Public');

function url(string $path = '/'): string {
  $path = '/' . ltrim($path, '/');
  return rtrim(BASE_URL, '/') . $path;
}

// Sesión global
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

// Rutas base (evita problemas de mayúsculas y paths)
define('APP_PATH',  dirname(__DIR__) . '/App');
define('VIEW_PATH', APP_PATH . '/Views'); // ⇐ App/Views


// DB (ajustamos credenciales si hiciera falta)
define('DB_DSN',  'mysql:host=localhost;dbname=prac_prof_final;charset=utf8mb4');
define('DB_USER', 'root');
define('DB_PASS', '');

// --- Email (Brevo SMTP) ---
define('MAIL_HOST', 'smtp-relay.brevo.com');
define('MAIL_PORT', 587);                     // TLS
define('MAIL_USER', '9854d6001@smtp-brevo.com');   // En Brevo, usuario = API key
define('MAIL_PASS', 'I7mqwD1n9AVCJWg6');   // y la contraseña = la misma API key
define('MAIL_FROM', 'proyectoqpay@gmail.com'); // remitente verificado en Brevo
define('MAIL_FROM_NAME', 'Q-Pay');

