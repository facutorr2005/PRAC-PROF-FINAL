<?php
namespace App\Controllers;                     

use App\Models\UsuarioModel;                  

class UsuariosController {                     
    private UsuarioModel $modelo;              

    public function __construct() {            
        $this->modelo = new UsuarioModel();    
    }

    
    private function renderizar(string $vistaRelativa, array $variables = []): void {
        extract($variables);                  
        $content = __DIR__ . '/../Views/' . $vistaRelativa; 
        include __DIR__ . '/../Views/layout.php';           
    }

    // GET /Usuarios/iniciarSesion
    public function iniciarSesion(): void {    // Muestra el formulario de login
        $titulo = 'Iniciar sesión';
        $ok    = $_GET['ok']    ?? null;       // Mensaje opcional (?ok=...)
        $error = $_GET['error'] ?? null;       // Mensaje de error (?error=...)
        $this->renderizar('Usuarios/iniciar_sesion.php', compact('titulo','ok','error'));
    }

    // POST /Usuarios/autenticar
    public function autenticar(): void {       // Procesa el login
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/Usuarios/iniciarSesion'); return;
        }
        $correo     = $_POST['correo']    ?? '';       // Trae datos del form
        $contrasena = $_POST['contrasena'] ?? '';

        if (trim($correo) === '' || $contrasena === '') {
            header('Location: ' . BASE_URL . '/Usuarios/iniciarSesion?error=' . urlencode('Completá correo y contraseña'));
            return;
        }

        $u = $this->modelo->obtenerPorCorreo($correo); // Busca el usuario por correo
        if (!$u || !password_verify($contrasena, $u->ContrasenaHash)) { // Verifica hash
            header('Location: ' . BASE_URL . '/Usuarios/iniciarSesion?error=' . urlencode('Credenciales inválidas'));
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); } //  Sesión
        session_regenerate_id(true);               // Evita fijación de sesión
        $_SESSION['usuario_id']      = (int)$u->Id;
        $_SESSION['usuario_correo']  = $u->Correo;
        $_SESSION['usuario_nombre']  = $u->Nombre;
        $_SESSION['usuario_apellido']= $u->Apellido;

        header('Location: ' . BASE_URL . '/Usuarios/panel'); // Redirige a zona privada
    }

    // GET /Usuarios/registrar
    public function registrar(): void {        // Muestra el formulario de registro
        $titulo = 'Crear cuenta';
        $error  = $_GET['error'] ?? null;
        $this->renderizar('Usuarios/registrar.php', compact('titulo','error'));
    }

    // POST /Usuarios/guardar
    public function guardar(): void {          // Procesa el registro
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/Usuarios/registrar'); return;
        }
        // Tomamos y limpiamos datos
        $nombre   = trim($_POST['nombre']  ?? '');
        $apellido = trim($_POST['apellido']?? '');
        $correo   = trim($_POST['correo']  ?? '');
        $pass     = (string)($_POST['contrasena']  ?? '');
        $pass2    = (string)($_POST['contrasena2'] ?? '');
        $fecha    = trim($_POST['fecha_nacimiento'] ?? ''); // YYYY-MM-DD
        $dni      = trim($_POST['dni'] ?? '');

        // Validaciones básicas
        if ($nombre==='' || $apellido==='' || $correo==='' || $pass==='' || $pass2==='' || $fecha==='' || $dni==='') {
            header('Location: ' . BASE_URL . '/Usuarios/registrar?error=' . urlencode('Completá todos los campos')); return;
        }
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            header('Location: ' . BASE_URL . '/Usuarios/registrar?error=' . urlencode('Correo inválido')); return;
        }
        if (strlen($pass) < 8) {
            header('Location: ' . BASE_URL . '/Usuarios/registrar?error=' . urlencode('La contraseña debe tener al menos 8 caracteres')); return;
        }
        if ($pass !== $pass2) {
            header('Location: ' . BASE_URL . '/Usuarios/registrar?error=' . urlencode('Las contraseñas no coinciden')); return;
        }
        if (!preg_match('/^\d{7,10}$/', $dni)) {       // DNI 7-10 dígitos
            header('Location: ' . BASE_URL . '/Usuarios/registrar?error=' . urlencode('DNI inválido')); return;
        }
        $t = strtotime($fecha);                        // Valida fecha
        if ($t === false) {
            header('Location: ' . BASE_URL . '/Usuarios/registrar?error=' . urlencode('Fecha de nacimiento inválida')); return;
        }

        // Chequeo de unicidad rápido (opcional, además del UNIQUE de la BD)
        if ($this->modelo->obtenerPorCorreo($correo)) {
            header('Location: ' . BASE_URL . '/Usuarios/registrar?error=' . urlencode('Ese correo ya está registrado')); return;
        }
        // Si implementamos obtenerPorDni en el modelo, podemos chequear también DNI.

        // Crear usuario
        $ok = $this->modelo->crear($correo, $pass, $nombre, $apellido, date('Y-m-d', $t), $dni);
        if (!$ok) {
            header('Location: ' . BASE_URL . '/Usuarios/registrar?error=' . urlencode('No se pudo crear la cuenta (¿correo/DNI duplicado?)')); return;
        }

        header('Location: ' . BASE_URL . '/Usuarios/iniciarSesion?ok=' . urlencode('Cuenta creada. Ahora podés iniciar sesión'));
    }

    // GET /Usuarios/panel
    public function panel(): void {            // Zona privada
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
        if (empty($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/Usuarios/iniciarSesion?error=' . urlencode('Debés iniciar sesión')); return;
        }
        $titulo   = 'Panel';
        $correo   = $_SESSION['usuario_correo']   ?? '';
        $nombre   = $_SESSION['usuario_nombre']   ?? '';
        $apellido = $_SESSION['usuario_apellido'] ?? '';
        $this->renderizar('Usuarios/panel.php', compact('titulo','correo','nombre','apellido'));
    }

    // GET /Usuarios/cerrarSesion
    public function cerrarSesion(): void {     // Cierra sesión
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
        $_SESSION = [];
        session_destroy();
        header('Location: ' . BASE_URL . '/Usuarios/iniciarSesion?ok=' . urlencode('Sesión cerrada'));
    }
}
