<?php
namespace App\Controllers;


require_once __DIR__ . '/../Lib/Seguridad.php'; 

use PDO;
use App\Lib\Correo;
use function App\Lib\generar_codigo_6;
use function App\Lib\hash_codigo;

class ContrasenasController {
    private PDO $db;
    private Correo $correo;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->correo = new Correo(require __DIR__.'/../config/correo.php');
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    }

    public function olvidada(): void {
        require __DIR__."/../Views/Contrasenas/olvidada.php";
    }

    public function enviar_codigo(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: '.BASE_URL.'/Contrasenas/olvidada'); exit; }

        $correoIngresado = filter_input(INPUT_POST, 'correo', FILTER_VALIDATE_EMAIL);
        if (!$correoIngresado) { $_SESSION['error']="Correo inválido"; header('Location: '.BASE_URL.'/Contrasenas/olvidada'); exit; }

        // Tu tabla: Usuarios (PK: Id, email: Correo)
        $st = $this->db->prepare("SELECT `Id`, `Correo` FROM `Usuarios` WHERE `Correo` = ?");
        $st->execute([$correoIngresado]);
        $usuario = $st->fetch(PDO::FETCH_ASSOC);

        $mensaje_ok = "Si el correo existe, te enviamos un código.";

        if (!$usuario) { $_SESSION['ok']=$mensaje_ok; header('Location: '.BASE_URL.'/Contrasenas/verificar_form'); exit; }

        // Limpieza
        $this->db->prepare("DELETE FROM `restablecimientos_contrasena` WHERE `usuario_id`=? AND (`usado`=1 OR `vence_el` < NOW())")
                 ->execute([$usuario['Id']]);

        $codigo = generar_codigo_6();
        $hash   = hash_codigo($codigo);
        $vence  = (new \DateTime('+15 minutes'))->format('Y-m-d H:i:s');

        $ins = $this->db->prepare("INSERT INTO `restablecimientos_contrasena` (`usuario_id`,`codigo_hash`,`vence_el`) VALUES (?,?,?)");
        $ins->execute([$usuario['Id'], $hash, $vence]);

        $ok = $this->correo->enviar_codigo($correoIngresado, $codigo);

        $_SESSION[$ok ? 'ok' : 'error'] = $ok ? $mensaje_ok : "No pudimos enviar el correo. Intentá más tarde.";
        header('Location: '.BASE_URL.'/Contrasenas/verificar_form');
    }

    public function verificar_form(): void {
        require __DIR__."/../Views/Contrasenas/verificar.php";
    }

    public function verificar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: '.BASE_URL.'/Contrasenas/verificar_form'); exit; }

        $correo = filter_input(INPUT_POST, 'correo', FILTER_VALIDATE_EMAIL);
        $codigo = trim($_POST['codigo'] ?? '');

        if (!$correo || !preg_match('/^\d{6}$/', $codigo)) {
            $_SESSION['error'] = "Datos inválidos."; header('Location: '.BASE_URL.'/Contrasenas/verificar_form'); exit;
        }

        $st = $this->db->prepare("SELECT `Id` FROM `Usuarios` WHERE `Correo`=?");
        $st->execute([$correo]);
        $usuario = $st->fetch(PDO::FETCH_ASSOC);
        if (!$usuario) { $_SESSION['error']="Código inválido o vencido."; header('Location: '.BASE_URL.'/Contrasenas/verificar_form'); exit; }

        $st = $this->db->prepare("SELECT * FROM `restablecimientos_contrasena`
                                  WHERE `usuario_id`=? AND `usado`=0 AND `vence_el`>=NOW()
                                  ORDER BY `id` DESC LIMIT 1");
        $st->execute([$usuario['Id']]);
        $rc = $st->fetch(PDO::FETCH_ASSOC);
        if (!$rc) { $_SESSION['error']="Código inválido o vencido."; header('Location: '.BASE_URL.'/Contrasenas/verificar_form'); exit; }

        if ((int)$rc['intentos'] >= 5) {
            $_SESSION['error']="Demasiados intentos. Pedí un nuevo código.";
            header('Location: '.BASE_URL.'/Contrasenas/olvidada'); exit;
        }

        $valido = password_verify($codigo, $rc['codigo_hash']);
        $this->db->prepare("UPDATE `restablecimientos_contrasena` SET `intentos`=`intentos`+1 WHERE `id`=?")->execute([$rc['id']]);

        if (!$valido) {
            $_SESSION['error']="Código incorrecto."; header('Location: '.BASE_URL.'/Contrasenas/verificar_form'); exit;
        }

        $this->db->prepare("UPDATE `restablecimientos_contrasena` SET `usado`=1 WHERE `id`=?")->execute([$rc['id']]);
        $_SESSION['usuario_id_para_restablecer'] = (int)$usuario['Id'];
        header('Location: '.BASE_URL.'/Contrasenas/nueva');
    }

    public function nueva(): void {
        if (empty($_SESSION['usuario_id_para_restablecer'])) { header('Location: '.BASE_URL.'/Contrasenas/olvidada'); exit; }
        require __DIR__."/../Views/Contrasenas/nueva.php";
    }

    public function actualizar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['usuario_id_para_restablecer'])) {
            header('Location: '.BASE_URL.'/Contrasenas/olvidada'); exit;
        }
        $c1 = $_POST['contrasena']  ?? '';
        $c2 = $_POST['contrasena2'] ?? '';
        if ($c1 !== $c2 || strlen($c1) < 8) {
            $_SESSION['error']="La contraseña debe coincidir y tener al menos 8 caracteres.";
            header('Location: '.BASE_URL.'/Contrasenas/nueva'); exit;
        }

        $hash = password_hash($c1, PASSWORD_BCRYPT);

        $this->db->beginTransaction();
        try {
            $usuarioId = (int)$_SESSION['usuario_id_para_restablecer'];

            // Tu columna de hash:
            $up = $this->db->prepare("UPDATE `Usuarios` SET `ContrasenaHash`=? WHERE `Id`=?");
            $up->execute([$hash, $usuarioId]);

            $this->db->prepare("UPDATE `restablecimientos_contrasena` SET `usado`=1 WHERE `usuario_id`=? AND `usado`=0")
                     ->execute([$usuarioId]);

            $this->db->commit();
            unset($_SESSION['usuario_id_para_restablecer']);
            $_SESSION['ok'] = "Tu contraseña fue actualizada.";
            header('Location: '.BASE_URL.'/Usuarios/iniciarSesion'); exit;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            $_SESSION['error'] = "No se pudo actualizar. Intentá de nuevo.";
            header('Location: '.BASE_URL.'/Contrasenas/nueva');
        }
    }
}
