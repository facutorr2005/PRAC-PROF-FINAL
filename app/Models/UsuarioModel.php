<?php
namespace App\Models;

use App\Database\Database;
use PDO;
use PDOException;

class UsuarioModel {
    public $Id;
    public $Correo;
    public $ContrasenaHash;
    public $Nombre;
    public $Apellido;
    public $FechaNacimiento; // YYYY-MM-DD
    public $Dni;

    private PDO $bd;

    public function __construct() {
        $this->bd = Database::getInstance()->getConnection();
    }

    public function obtenerPorCorreo(string $correo): ?object {
        $sql = "SELECT Id, Correo, ContrasenaHash, Nombre, Apellido, FechaNacimiento, Dni
                FROM Usuarios WHERE Correo = :correo LIMIT 1";
        $st = $this->bd->prepare($sql);
        $st->execute([':correo' => mb_strtolower(trim($correo))]);
        $fila = $st->fetch(PDO::FETCH_ASSOC);
        return $fila ? (object)$fila : null;
    }

    public function obtenerPorDni(string $dni): ?object {
        $sql = "SELECT Id, Correo, ContrasenaHash, Nombre, Apellido, FechaNacimiento, Dni
                FROM Usuarios WHERE Dni = :dni LIMIT 1";
        $st = $this->bd->prepare($sql);
        $st->execute([':dni' => trim($dni)]);
        $fila = $st->fetch(PDO::FETCH_ASSOC);
        return $fila ? (object)$fila : null;
    }

    public function obtenerPorId(int $id): ?object {
        $sql = "SELECT Id, Correo, ContrasenaHash, Nombre, Apellido, FechaNacimiento, Dni
                FROM Usuarios WHERE Id = :id LIMIT 1";
        $st = $this->bd->prepare($sql);
        $st->execute([':id' => $id]);
        $fila = $st->fetch(PDO::FETCH_ASSOC);
        return $fila ? (object)$fila : null;
    }

    public function crear(
        string $correo, string $contrasenaPlano,
        string $nombre, string $apellido,
        string $fechaNacimiento, string $dni
    ): bool {
        $hash = password_hash($contrasenaPlano, PASSWORD_DEFAULT);
        $sql = "INSERT INTO Usuarios (Correo, ContrasenaHash, Nombre, Apellido, FechaNacimiento, Dni)
                VALUES (:correo, :hash, :nombre, :apellido, :fecha, :dni)";
        $st = $this->bd->prepare($sql);
        try {
            return $st->execute([
                ':correo'   => mb_strtolower(trim($correo)),
                ':hash'     => $hash,
                ':nombre'   => trim($nombre),
                ':apellido' => trim($apellido),
                ':fecha'    => $fechaNacimiento, // YYYY-MM-DD
                ':dni'      => trim($dni),
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') return false; // correo o DNI duplicado (UNIQUE)
            throw $e;
        }
    }
}

