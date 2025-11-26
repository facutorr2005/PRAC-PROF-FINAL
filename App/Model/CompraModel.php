<?php

namespace App\Models;

use PDO;
use PDOException;

class CompraModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO(DB_DSN, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    /**
     * Registra una compra en transacciones + transacciones_detalles
     * $items = [
     *   ['ean' => '779...', 'cantidad' => 2, 'precio' => 1234.50],
     *   ...
     * ]
     */
    public function registrarCompra(array $items, int $id_usuario): int
    {
        if (empty($items)) {
            throw new \InvalidArgumentException('No hay ítems en la compra.');
        }

        try {
            $this->db->beginTransaction();

            // Inserto la transacción, asociándola al usuario.
            // NOTA: Se asume que la tabla `transacciones` tiene una columna `id_usuario`.
            $sqlTx = "INSERT INTO transacciones (id_usuario, Momento) VALUES (?, NOW())";
            $stTx = $this->db->prepare($sqlTx);
            $stTx->execute([$id_usuario]);
            $idTransaccion = (int)$this->db->lastInsertId();

            // Detalles
            $sqlDet = "INSERT INTO transacciones_detalles
                       (IDtransaccion, CodigoEAN, Cantidad, PrecioVenta)
                       VALUES (?, ?, ?, ?)";
            $stDet = $this->db->prepare($sqlDet);

            foreach ($items as $item) {
                $ean      = $item['ean']      ?? null;
                $cantidad = $item['cantidad'] ?? null;
                $precio   = $item['precio']   ?? null;

                if (!$ean || !$cantidad || !$precio) {
                    throw new \RuntimeException('Ítem de compra inválido');
                }

                $stDet->execute([
                    $idTransaccion,
                    $ean,
                    (int)$cantidad,
                    (float)$precio,
                ]);
            }

            $this->db->commit();
            return $idTransaccion;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Devuelve historial de compras (ID, Momento, Total).
     * Saca el total de la suma Cantidad * PrecioVenta.
     */
    public function obtenerHistorial(): array
    {
        $sql = "SELECT 
                    t.ID,
                    t.Momento,
                    COALESCE(SUM(d.Cantidad * d.PrecioVenta), 0) AS Total
                FROM transacciones t
                LEFT JOIN transacciones_detalles d
                  ON d.IDtransaccion = t.ID
                GROUP BY t.ID, t.Momento
                ORDER BY t.Momento DESC";

        $st = $this->db->query($sql);
        return $st->fetchAll();
    }

    /**
     * Devuelve los detalles de una compra específica (productos),
     * verificando que pertenezca al usuario.
     */
    public function obtenerDetallePorId(int $id, int $id_usuario): array
    {
        $sql = "SELECT
                    p.nombre,
                    d.Cantidad AS cantidad,
                    d.PrecioVenta AS precio_unitario
                FROM transacciones_detalles d
                JOIN productos p ON d.CodigoEAN = p.ean
                JOIN transacciones t ON d.IDtransaccion = t.ID
                WHERE d.IDtransaccion = ? AND t.id_usuario = ?";

        $st = $this->db->prepare($sql);
        $st->execute([$id, $id_usuario]);
        return $st->fetchAll();
    }

    /**
     * Obtiene una transacción por su ID, verificando que pertenezca al usuario.
     */
    public function obtenerPorId(int $id, int $id_usuario): ?array
    {
        $sql = "SELECT * FROM transacciones WHERE ID = ? AND id_usuario = ?";
        $st = $this->db->prepare($sql);
        $st->execute([$id, $id_usuario]);
        $result = $st->fetch();
        return $result ?: null;
    }
}
