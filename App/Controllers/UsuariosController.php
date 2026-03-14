<?php
namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Core\Mailer;

use DateTime;
use DateTimeZone;
use DateTimeImmutable;



class UsuariosController{

    private UsuarioModel $model;

    public function __construct(){

        if (session_status() !== \PHP_SESSION_ACTIVE) { session_start(); }
        $this->model = new UsuarioModel();
    }

    private function render(string $viewRelPath, array $vars = []): void{
        // variables para la vista
        extract($vars, EXTR_SKIP);

        // arma el path absoluto a la vista
        $file = rtrim(VIEW_PATH, '/\\') . DIRECTORY_SEPARATOR . ltrim($viewRelPath, '/\\');

        if (!is_file($file)){
            http_response_code(500);
            echo 'Vista no encontrada: ' . htmlspecialchars($viewRelPath);
        return;
        }

        include $file;
    }



    // GET /login
    public function loginForm(): void{

        if (!empty($_SESSION['user_id'])) {
            header('Location: ' . url('/panel'));
        return;
        }

        $this->render('Usuarios/login.php');
    }

    // POST /login
    public function login(): void{

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: ' . url('/login')); return;
        }

        $email = trim($_POST['correo'] ?? '');
        $pass  = trim($_POST['contrasena'] ?? '');

        if ($email === '' || $pass === '') {
            $_SESSION['error'] = 'Ingresá email y contraseña';
            header('Location: ' . url('/login')); return;
        }

        $user = $this->model->obtenerPorEmail($email);

        if (!$user || !password_verify($pass, $user['PasswordHash'])) {
            $_SESSION['error'] = 'Usuario o contraseña incorrectos';
            header('Location: ' . url('/login')); return;
        }

        // OK
        unset($_SESSION['error']);
        session_regenerate_id(true);
        $_SESSION['user_id']    = (int)$user['Id'];
        $_SESSION['user_email'] = $user['Email'];
        $_SESSION['user_name']  = $user['Nombre'] ?? null;

        header('Location: ' . url('/panel'));
    }

    // GET /panel (solo para confirmar que está logueado)

    // ==============================
    // REGISTRO DE USUARIOS
    // ==============================

    public function registerForm(): void {
        $this->render('Usuarios/registro.php');
    }

    public function register(): void {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: ' . url('/registro'));
            return;
        }

        // Tomar campos
        $nombre   = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $correo   = trim($_POST['correo'] ?? '');
        $dni      = trim($_POST['dni'] ?? '');
        $nac      = trim($_POST['fecha_nac'] ?? '');
        $pass     = $_POST['contrasena'] ?? '';
        $pass2    = $_POST['contrasena2'] ?? '';

        // Validaciones
        if ($nombre==='' || $apellido==='' || $correo==='' || $pass==='' || $pass2==='') {
            $_SESSION['error'] = 'Completá los campos obligatorios.';
            header('Location: ' . url('/registro')); return;
        }
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Correo inválido.';
            header('Location: ' . url('/registro')); return;
        }
        if ($pass !== $pass2) {
            $_SESSION['error'] = 'Las contraseñas no coinciden.';
            header('Location: ' . url('/registro')); return;
        }
        if ($this->model->emailExiste($correo)) {
            $_SESSION['error'] = 'Ya existe un usuario con ese correo.';
            header('Location: ' . url('/registro')); return;
        }

        // Crear usuario
        $id = $this->model->crear($nombre, $apellido, $correo, $dni, $nac, $pass);
        if ($id <= 0) {
            $_SESSION['error'] = 'No se pudo crear el usuario.';
            header('Location: ' . url('/registro')); return;
        }

        $_SESSION['ok'] = 'Cuenta creada. Iniciá sesión.';
        header('Location: ' . url('/login'));
    }


    // =====================================================
    // 🔐 RECUPERACIÓN DE CONTRASEÑA (con código por email)
    // Vistas usadas: 
    //   - Usuarios/recuperacion.php  (pedir correo)
    //   - Usuarios/digitos.php       (ingresar código)
    //   - Usuarios/editcon.php       (nueva contraseña)
    // =====================================================

    public function forgotForm(): void {
        $this->render('Usuarios/recuperacion.php');
    }

    public function forgot(): void {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        header('Location: ' . url('/recuperacion')); return;
        }

        $correo = trim($_POST['correo'] ?? '');
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Ingresá un correo válido.';
        header('Location: ' . url('/recuperacion')); return;
        }

        // Buscar usuario (si no existe, mensaje genérico)
        $usuario = $this->model->findByEmail($correo);
        if (!$usuario) {
        $_SESSION['ok'] = 'Si el correo existe, te enviamos un código.';
        header('Location: ' . url('/recuperacion')); return;
        }

        // Cooldown de reenvío (60s)
        if (!$this->model->puedeReenviarCodigo($correo, 60)) {
        $_SESSION['error'] = 'Esperá 1 minuto antes de solicitar otro código.';
        header('Location: ' . url('/recuperacion')); return;
        }

        // Generar código y guardar (hash + vencimiento)
        $codigo = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $vence = (new DateTime('now', new DateTimeZone(date_default_timezone_get())))
             ->modify('+15 minutes');


        $this->model->crearActualizarCodigo((int)$usuario['Id'], $correo, $codigo, $vence);


        // Generamos el contenido HTML del correo
        $html = <<<HTML
        <p>Tu código de recuperación es:</p>
        <h2 style="letter-spacing:3px;">{$codigo}</h2>
        <p>Vence en 15 minutos.</p>
        HTML;

        // Enviamos el correo
        $ok = Mailer::send(
        $correo,                       // destinatario
        $usuario['Nombre'] ?? '',       // nombre
        'Código de recuperación - Q-Pay', // asunto
        $html                           // contenido HTML
        );


        // Guardamos el correo en sesión para el siguiente paso
        $_SESSION['correo_recuperacion'] = $correo;

        $_SESSION['ok'] = $ok
        ? 'Si el correo existe, te enviamos un código.'
        : 'No pudimos enviar el email. Intentalo más tarde.';
        header('Location: ' . url('/codigo'));
    }

    public function codeForm(): void {
        // Importante: solo UNA definición de codeForm en la clase
        $this->render('Usuarios/digitos.php');
    }

    public function verifyCode(): void {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: ' . url('/codigo')); return;
        }

        // El correo lo tomamos de la sesión (lo guardamos en forgot())
        $correo = $_SESSION['correo_recuperacion'] ?? null;
        // Los 6 inputs deben llamarse name="codigo[]" en la vista
        $codigo = trim(implode('', $_POST['codigo'] ?? []));

        if (!$correo || strlen($codigo) !== 6) {
            $_SESSION['error'] = 'Completá correo y código.';
            header('Location: ' . url('/codigo')); return;
        }

        $fila = $this->model->obtenerFilaPorCorreo($correo);
        file_put_contents('/tmp/qpay_debug.log', "[CTRL] fila_vence={$fila['vence_el']} ahora=" . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

        if (!$fila) {
            $_SESSION['error'] = 'Código inválido.';
            header('Location: ' . url('/codigo')); return;
        }


        // --- ¿Vencido? comparación robusta con timestamps ---
        $venceStr = isset($fila['vence_el']) ? trim((string)$fila['vence_el']) : '';
        $venceTs  = $venceStr !== '' ? strtotime($venceStr) : false;  // espera 'YYYY-mm-dd HH:ii:ss'
        $ahoraTs  = time();

        if ($venceTs === false) {
            // No pude interpretar la fecha -> tratar como vencido y limpiar
            $_SESSION['error'] = 'No pude interpretar la fecha de vencimiento. Solicitá un código nuevo.';
            $this->model->eliminarRecuperacion((int)$fila['id']);
            header('Location: ' . url('/recuperacion')); return;
        }

        if ($ahoraTs >= $venceTs) {
            $_SESSION['error'] = 'Código vencido. Solicitá uno nuevo.';
            $this->model->eliminarRecuperacion((int)$fila['id']);
            header('Location: ' . url('/recuperacion')); return;
        }



        // ¿Demasiados intentos?
        if ((int)$fila['intentos'] >= 5) {
            $_SESSION['error'] = 'Demasiados intentos. Solicitá un código nuevo.';
            $this->model->eliminarRecuperacion((int)$fila['id']);
            header('Location: ' . url('/recuperacion')); return;
        }

        // Verificar código (el modelo guardó hash)
        if (!password_verify($codigo, $fila['codigo_hash'])) {
            $this->model->incrementarIntentos((int)$fila['id']);
            $_SESSION['error'] = 'Código incorrecto.';
            header('Location: ' . url('/codigo')); return;
        }

        // OK → habilitamos paso de reset
        $_SESSION['can_reset_user_id'] = (int)$fila['id_usuario'];
        $_SESSION['can_reset_correo']  = $fila['correo'];

        header('Location: ' . url('/reset'));
    }

    public function resetForm(): void {
        if (empty($_SESSION['can_reset_user_id'])) {
            header('Location: ' . url('/recuperacion')); return;
        }
        $this->render('Usuarios/editcon.php');
    }

    public function reset(): void {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: ' . url('/recuperacion')); return;
        }

        if (empty($_SESSION['can_reset_user_id'])) {
            $_SESSION['error'] = 'Sesión inválida.';
            header('Location: ' . url('/recuperacion')); return;
        }

        $p1 = trim($_POST['contrasena']  ?? '');
        $p2 = trim($_POST['repetir']     ?? '');

        if ($p1 === '' || $p2 === '') {
            $_SESSION['error'] = 'Completá ambos campos.';
            header('Location: ' . url('/reset')); return;
        }
        if ($p1 !== $p2) {
            $_SESSION['error'] = 'Las contraseñas no coinciden.';
            header('Location: ' . url('/reset')); return;
        }

        // Actualizar contraseña
        $ok = $this->model->updatePasswordById((int)$_SESSION['can_reset_user_id'], $p1);
        if (!$ok) {
            $_SESSION['error'] = 'No se pudo actualizar la contraseña.';
            header('Location: ' . url('/reset')); return;
        }

        // =========================================================
        // ✅ Borrar registro de recuperación y limpiar la sesión
        // =========================================================
        $fila = $this->model->obtenerFilaPorCorreo($_SESSION['can_reset_correo'] ?? null);

        if ($fila) {
            $this->model->eliminarRecuperacion((int)$fila['id']);
        }

        // Limpiar variables de sesión usadas en el flujo
        unset(
            $_SESSION['can_reset_user_id'],
            $_SESSION['can_reset_correo'],
            $_SESSION['correo_recuperacion']
        );

        // Mensaje final y redirección al login
        $_SESSION['ok'] = 'Contraseña actualizada. Iniciá sesión.';
        header('Location: ' . url('/login'));
    }

        // GET /panel  -> solo si está logueado
        public function panel(): void {
            if (empty($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Inicia sesión para continuar.';
            header('Location: ' . url('/login'));
            return;
            }

            // Podés pasar datos a la vista si querés
            $this->render('Usuarios/panel.php', [
                'nombre' => $_SESSION['user_name'] ?? '',
                'email'  => $_SESSION['user_email'] ?? '',
            ]);
        }
        
        public function perfil(): void{
            if (session_status() !== PHP_SESSION_ACTIVE) session_start();
            // Si no hay usuario logueado, redirige al login
            if (empty($_SESSION['user_id'])) {
                header('Location: ' . url('/login'));
                return;
            }

            // Obtener datos del usuario desde el modelo
            $user = $this->model->obtenerPorEmail($_SESSION['user_email']);

            // Renderizar la vista perfil con los datos del usuario
            $this->render('Usuarios/perfil.php', ['user' => $user]);
        }

        // POST /perfil/cambiar-password
        public function procesarCambioPassword(): void{
            // 1. Verificar sesión
            if (session_status() !== PHP_SESSION_ACTIVE) session_start();
            if (empty($_SESSION['user_id'])) {
                header('Location: ' . url('/login'));
                return;
            }

            // 2. Solo aceptar POST
            if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
                header('Location: ' . url('/perfil'));
                return;
            }

            // 3. Obtener datos
            $actual  = $_POST['pass_actual'] ?? '';
            $nueva   = $_POST['pass_nueva'] ?? '';
            $repetir = $_POST['pass_repetir'] ?? '';

            // 4. Validaciones
            if ($actual === '' || $nueva === '' || $repetir === '') {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                header('Location: ' . url('/perfil'));
                return;
            }

            if ($nueva !== $repetir) {
                $_SESSION['error'] = 'Las nuevas contraseñas no coinciden.';
                header('Location: ' . url('/perfil'));
                return;
            }

            if (strlen($nueva) < 6) {
                $_SESSION['error'] = 'La nueva contraseña es muy corta.';
                header('Location: ' . url('/perfil'));
                return;
            }

            // 5. Verificar contraseña ACTUAL (Seguridad Crítica)
            $idUsuario = (int)$_SESSION['user_id'];
            $user = $this->model->findById($idUsuario);

            if (!$user || !password_verify($actual, $user['PasswordHash'])) {
                $_SESSION['error'] = 'La contraseña ACTUAL es incorrecta.';
                header('Location: ' . url('/perfil'));
                return;
            }

            // 6. Guardar nueva contraseña
            // Usamos el método que ya tenías en tu Modelo: updatePasswordById
            $ok = $this->model->updatePasswordById($idUsuario, $nueva);

            if ($ok) {
                $_SESSION['ok'] = 'Contraseña actualizada correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar en base de datos.';
            }

            header('Location: ' . url('/perfil'));
        }


        // ========================
        // LOGOUT DEL USUARIO
        // ========================
        public function logout(): void{

        // Aceptar GET o POST (según cómo lo invoques desde el panel)
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Vaciar todas las variables de sesión
        $_SESSION = [];

        // Eliminar la cookie de sesión si existe
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            // En algunos PHP 'domain' puede ser null; usamos null coalesce
            setcookie(
                session_name(), '', time() - 42000,
                $p['path'] ?? '/',
                $p['domain'] ?? '',
                $p['secure'] ?? false,
                $p['httponly'] ?? true
            );
        }

        // Destruir la sesión
        session_destroy();

        // Mensaje flash opcional (nuevo id de sesión limpio)
        session_start();
        $_SESSION['ok'] = 'Sesión cerrada correctamente.';

        // Redirigir al login
        header('Location: ' . url('/login'));
        exit;
    }

    public function confirmarEliminacion(): void{

        // Debe estar logueado
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . url('/login'));
            return;
        }

        // GET -> mostrar formulario
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $this->render('Usuarios/confirmareliminacion.php');
            return;
        }

        // POST -> procesar eliminación
        $pass1 = trim($_POST['contrasena'] ?? '');
        $pass2 = trim($_POST['repetir'] ?? '');

        if ($pass1 === '' || $pass2 === '') {
            $_SESSION['error'] = 'Completá ambos campos.';
            header('Location: ' . url('/confirmareliminacion'));
            return;
        }
        if ($pass1 !== $pass2) {
            $_SESSION['error'] = 'Las contraseñas no coinciden.';
            header('Location: ' . url('/confirmareliminacion'));
            return;
        }

        // Traer usuario actual y validar contraseña
        $uid   = (int) $_SESSION['user_id'];
        $user  = $this->model->findById($uid);

        if (!$user || !password_verify($pass1, $user['PasswordHash'])) {
            $_SESSION['error'] = 'Contraseña incorrecta.';
            header('Location: ' . url('/confirmareliminacion'));
            return;
        }

        // Borrar usuario en BD
        $ok = $this->model->eliminarPorId($uid);
        if (!$ok) {
            $_SESSION['error'] = 'No pudimos eliminar tu cuenta. Intentá más tarde.';
            header('Location: ' . url('/confirmareliminacion'));
            return;
        }

        // Cerrar sesión de forma segura
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'] ?? '/', $p['domain'] ?? '', $p['secure'] ?? false, $p['httponly'] ?? true);
        }
        session_destroy();

        // Iniciar una sesión limpia solo para el flash
        session_start();
        $_SESSION['ok'] = 'Tu cuenta fue eliminada con éxito.';
        header('Location: ' . url('/login'));
    }
}

  
