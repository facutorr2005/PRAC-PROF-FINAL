<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Compra</title>
    <link rel="stylesheet" href="<?= url('/css/detalle.css') ?>">
</head>
<body>
    <div class="detalle-container">
        <h1>Detalle de Compra</h1>

        <div class="lista-productos">
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <div class="producto">
                        <span class="producto-info"><?= htmlspecialchars($producto['nombre']) ?></span>
                        <span class="producto-cantidad">Cantidad: <?= $producto['cantidad'] ?></span>
                        <span class="producto-precio">$<?= number_format($producto['precio_unitario'] * $producto['cantidad'], 2) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No se encontraron productos para esta compra.</p>
            <?php endif; ?>
        </div>

        <div class="total">
            Total: $<?= number_format($total ?? 0, 2) ?>
        </div>
    </div>
</body>
</html>
