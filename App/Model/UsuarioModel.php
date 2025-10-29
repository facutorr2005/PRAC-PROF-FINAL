<?php
namespace App\Models;

use PDO;

class UsuarioModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO(DB_DSN, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    /** Devuelve usuario por email (o null si no existe) */
    public function obtenerPorEmail(string $email): ?array
    {
        // Unificada a columna Email
        $sql = "SELECT Id, Nombre, Apellido, Email, PasswordHash
                FROM usuarios
                WHERE Email = ?
                LIMIT 1";
        $st = $this->db->prepare($sql);
        $st->execute([$email]);
        $row = $st->fetch();
        return $row ?: null;
    }

    /** Alias conveniente para el controller */
    public function findByEmail(string $email): ?array
    {
        return $this->obtenerPorEmail($email);
    }

    /** ¿Existe el email? */
    public function emailExiste(string $email): bool
    {
        // Unificada a columna Email (antes decía Correo)
        $sql = "SELECT 1 FROM usuarios WHERE Email = ? LIMIT 1";
        $st  = $this->db->prepare($sql);
        $st->execute([$email]);
        return (bool)$st->fetchColumn();
    }

    /** Crear usuario */
    public function crear(
        string $nombre,
        string $apellido,
        string $email,
        string $dni,
        string $fechaNac,
        string $password
    ): int {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Unificada a columna Email (antes decía Correo)
        $sql = "INSERT INTO usuarios (Nombre, Apellido, Email, DNI, FechaNacimiento, PasswordHash)
                VALUES (?, ?, ?, ?, ?, ?)";
        $st = $this->db->prepare($sql);
        $st->execute([$nombre, $apellido, $email, $dni, $fechaNac, $hash]);

        return (int)$this->db->lastInsertId();
    }

    /** Crear/actualizar código de recuperación */
    public function crearActualizarCodigo(int $idUsuario, string $correo, string $codigo, \DateTime $vence): void
    {
        // Tabla de recuperación (dejé los nombres que usaste: correo, codigo_hash, vence_el, intentos, enviado_el)
        $ahora = (new \DateTime())->format('Y-m-d H:i:s');

        $sql = "INSERT INTO recuperacion_contrasena (id_usuario, correo, codigo_hash, vence_el, intentos, enviado_el)
                VALUES (?, ?, ?, ?, 0, ?)
                ON DUPLICATE KEY UPDATE
                  codigo_hash = VALUES(codigo_hash),
                  vence_el    = VALUES(vence_el),
                  intentos    = 0,
                  enviado_el  = VALUES(enviado_el)";
        $st = $this->db->prepare($sql);
        $st->execute([$idUsuario, $correo, password_hash($codigo, PASSWORD_DEFAULT), $vence->format('Y-m-d H:i:s'), $ahora]);
    }

    /** Antiflood de reenvío */
    public function puedeReenviarCodigo(string $correo, int $cooldownSegundos = 60): bool
    {
        $st = $this->db->prepare("SELECT enviado_el FROM recuperacion_contrasena WHERE correo = ? LIMIT 1");
        $st->execute([$correo]);
        $fila = $st->fetch();
        if (!$fila) return true;

        $ultimo = new \DateTime($fila['enviado_el']);
        return (time() - $ultimo->getTimestamp()) >= $cooldownSegundos;
    }

    /** Obtener registro de recuperación por correo */
    public function obtenerFilaPorCorreo(string $correo): ?array
    {
        $st = $this->db->prepare("SELECT * FROM recuperacion_contrasena WHERE correo = ? LIMIT 1");
        $st->execute([$correo]);
        $fila = $st->fetch();
        return $fila ?: null;
    }

    /** +1 intento */
    public function incrementarIntentos(int $id): void
    {
        $this->db->prepare("UPDATE recuperacion_contrasena SET intentos = intentos + 1 WHERE id = ? LIMIT 1")
                 ->execute([$id]);
    }

    /** Eliminar registro de recuperación */
    public function eliminarRecuperacion(int $id): void
    {
        $this->db->prepare("DELETE FROM recuperacion_contrasena WHERE id = ? LIMIT 1")
                 ->execute([$id]);
    }

    /** Verificar código (hash) y vencimiento */
    public function verificarCodigo(string $correo, string $codigo): bool
    {
        $st = $this->db->prepare("SELECT codigo_hash, vence_el FROM recuperacion_contrasena WHERE correo = ? LIMIT 1");
        $st->execute([$correo]);
        $fila = $st->fetch();
        if (!$fila) return false;

        // vencimiento
        if (new \DateTime() > new \DateTime($fila['vence_el'])) {
            return false;
        }

        // hash del código
        return password_verify($codigo, $fila['codigo_hash']);
    }

    /** Actualizar password por ID de usuario */
    public function updatePasswordById(int $userId, string $plain): bool
    {
        $hash = password_hash($plain, PASSWORD_DEFAULT);
        $st = $this->db->prepare("UPDATE usuarios SET PasswordHash = ? WHERE Id = ? LIMIT 1");
        return $st->execute([$hash, $userId]);
    }

    /** Actualizar contraseña por correo (para el último paso si preferís por email) */
    public function actualizarContrasena(string $correo, string $hash): void
    {
        $this->db->prepare("UPDATE usuarios SET PasswordHash = ? WHERE Email = ? LIMIT 1")
                 ->execute([$hash, $correo]);
    }
}

