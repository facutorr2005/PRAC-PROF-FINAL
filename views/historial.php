<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Public/css/historial.css">
</head>

<body>
    <div class="encabezado">

        <div class="titulo">
            Q-Pay
        </div>

        <button class="boton" onclick="location.href='panel.php'">
            Volver al inicio
        </button>

    </div>

    <div class="historial">
        <div class="subtitulo">
            Historial de Compras
        </div>
        <div>
            <?php foreach ($compras as $c): ?>
            <?= $c->Sucursal ?> -
            <?= $c->Direccion ?> -
            <?= $c->Fecha ?> - Total: 
            <?= $c->Precio ?>
            <button class="boton-foreach" onclick="location.href='<?= BASE_URL ?>/compras/consultar/<?= $c->Id ?>'">
                Consultar
            </button>

            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>