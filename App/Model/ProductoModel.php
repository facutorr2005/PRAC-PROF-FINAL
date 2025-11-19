<?php

namespace App\Models;

use PDO;

class ProductoModel{
    
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO(DB_DSN, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    /**
     * Busca un producto por Código EAN.
     * Devuelve array con claves:
     *  - ean
     *  - nombre
     *  - precio
     *  - imagen
     * o null si no existe.
     */
    public function buscarPorEAN(string $ean): ?array
    {
        $sql = "SELECT CodigoEAN, Nombre, PrecioVenta, Imagen
                FROM productos
                WHERE CodigoEAN = ?
                LIMIT 1";
        $st = $this->db->prepare($sql);
        $st->execute([$ean]);
        $row = $st->fetch();

        if (!$row) {
            return null;
        }

        return [
            'ean'    => $row['CodigoEAN'],
            'nombre' => $row['Nombre'],
            'precio' => (float)$row['PrecioVenta'],
            // si tus imágenes están en /Public, podés ajustar esto:
            'imagen' => '/' . ltrim($row['Imagen'], '/'),
        ];
    }
}
