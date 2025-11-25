<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\CompraModel;

class ComprasController{
    
    private ProductoModel $productos;
    private CompraModel $compras;

    public function __construct(){

        if (session_status() !== \PHP_SESSION_ACTIVE) {
            session_start();
        }

        $this->productos = new ProductoModel();
        $this->compras   = new CompraModel();
    }

    private function render(string $viewRelPath, array $vars = []): void{
        extract($vars, EXTR_SKIP);

        $file = rtrim(VIEW_PATH, '/\\') . DIRECTORY_SEPARATOR . ltrim($viewRelPath, '/\\');

        if (!is_file($file)) {
            http_response_code(500);
            echo 'Vista no encontrada: ' . htmlspecialchars($viewRelPath);
            return;
        }

        include $file;
    }

    // ==========================
    //  GET /compra → muestra la vista del carrito
    // ==========================
    public function compraForm(): void{

        if (empty($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Inicia sesión para comprar.';
            header('Location: ' . url('/login'));
            return;
        }

        $this->render('Compras/compra.php');

    }

    // ==========================
    //  GET /historial → lista de compras
    // ==========================
    public function historial(): void{

        if (empty($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Inicia sesión para ver tu historial.';
            header('Location: ' . url('/login'));
            return;
        }

        $compras = $this->compras->obtenerHistorial();

        $this->render('Compras/historial.php', ['compras' => $compras]);

    }

    // ==========================
    //  GET /api/producto?ean=...
    //  Devuelve JSON para el scan
    // ==========================
    public function apiBuscarProducto(): void{

        header('Content-Type: application/json; charset=utf-8');

        $ean = trim($_GET['ean'] ?? '');
        if ($ean === '') {
            echo json_encode([]);
            return;
        }

        $producto = $this->productos->buscarPorEAN($ean);
        if (!$producto) {
            echo json_encode([]);
            return;
        }

        echo json_encode($producto);
    }

    // ==========================
    //  POST /api/compra
    //  Recibe JSON del carrito y guarda en BD
    // ==========================
    public function apiGuardarCompra(): void{
        
        header('Content-Type: application/json; charset=utf-8');

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            return;
        }

        $raw = file_get_contents('php://input');
        $items = json_decode($raw, true);

        if (!is_array($items) || empty($items)) {
            echo json_encode(['success' => false, 'error' => 'Carrito vacío']);
            return;
        }

        // Seguridad mínima: normalizar estructura
        $normalizados = [];
        foreach ($items as $item) {
            $normalizados[] = [
                'ean'      => $item['ean']      ?? '',
                'cantidad' => (int)($item['cantidad'] ?? 0),
                'precio'   => (float)($item['precio'] ?? 0),
            ];
        }

        // --- INICIO PARCHE DE SEGURIDAD ---
        if (empty($_SESSION['user_id'])) {
            http_response_code(403); // Forbidden
            echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
            return;
        }
        $id_usuario = (int)$_SESSION['user_id'];
        // --- FIN PARCHE DE SEGURIDAD ---

        try {
            $id = $this->compras->registrarCompra($normalizados, $id_usuario);
            echo json_encode(['success' => true, 'id_transaccion' => $id]);
        } catch (\Throwable $e) {
            // En prod: loggear, no mostrar detalle
            echo json_encode(['success' => false, 'error' => 'No se pudo guardar la compra']);
        }
    }

    // ==========================
    //  GET /compras/qr/{id} → muestra el QR de una compra
    // ==========================
    public function qr(int $id): void
    {
        if (empty($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Inicia sesión para ver tus compras.';
            header('Location: ' . url('/login'));
            return;
        }

        // --- INICIO PARCHE DE SEGURIDAD ---
        $id_usuario = (int)$_SESSION['user_id'];
        $compra = $this->compras->obtenerPorId($id, $id_usuario);

        if (!$compra) {
            http_response_code(404);
            echo "Compra no encontrada o no te pertenece.";
            return;
        }
        // --- FIN PARCHE DE SEGURIDAD ---

        $this->render('Compras/qr.php', ['id_transaccion' => $id]);
    }

    // ==========================
    //  GET /compras/detalle/{id} → muestra el detalle de una compra
    // ==========================
    public function detalle(int $id): void
    {
        if (empty($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Inicia sesión para ver tus compras.';
            header('Location: ' . url('/login'));
            return;
        }

        $id_usuario = (int)$_SESSION['user_id'];
        $productos = $this->compras->obtenerDetallePorId($id, $id_usuario);

        // Si no se encontraron productos, es porque la compra no existe o no pertenece al usuario.
        if (empty($productos)) {
            http_response_code(404);
            echo "Compra no encontrada o no te pertenece.";
            return;
        }

        $total = 0;
        foreach ($productos as $p) {
            $total += $p['cantidad'] * $p['precio_unitario'];
        }

        $this->render('Compras/detalle.php', [
            'productos' => $productos,
            'total' => $total
        ]);
    }
}
