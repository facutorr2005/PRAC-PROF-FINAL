<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('/css/historial.css') ?>">
</head>

<body>
    <div class="encabezado">

        <div class="logo-titulo">
            <img class="logo" src="<?= url('/imagenes/logo.png') ?>" alt="logo">
            <div class="titulo">
                Q-Pay
            </div>
        </div>

        <button class="boton" onclick="location.href='<?= url('/panel') ?>'">
            Volver al inicio
        </button>

    </div>

    <div class="historial">
        <div class="subtitulo">
            Historial de Compras
        </div>
            
        <?php /** @var array $compras */ ?>

        <?php if (empty($compras)): ?>
            <div class="mensaje-vacio">
                Aún no has realizado ninguna compra.
            </div>
        <?php else: ?>

            <?php foreach ($compras as $c): ?>
                <div class="compra-card">
                    
                    <div class="info-compra">
                        <span class="dato-id">ID #<?= htmlspecialchars($c['ID']) ?></span>
                        <span class="dato-fecha"><?= date('d/m/Y H:i', strtotime($c['Momento'])) ?> hs</span>
                        <span class="dato-total">Total: $<?= number_format($c['Total'], 2, ',', '.') ?></span>
                    </div>

                    <div class="acciones">
                        <a href="<?= url('/compras/detalle/' . $c['ID']) ?>" class="btn-accion btn-detalle">
                            👁️ Ver Productos
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>    

        <?php endif; ?>
    </div>

</body>
</html>