<!DOCTYPE html>
<html lang="en">

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
            <img class="logo" src="../Public/imagenes/logo.png" alt="logo">
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

            <?php foreach ($compras as $c): ?>
                <div class="compra">
                    ID #<?= htmlspecialchars($c['ID']) ?> -
                    Fecha: <?= htmlspecialchars($c['Momento']) ?> -
                    Total: $<?= number_format($c['Total'], 2, ',', '.') ?>

                    <!-- Botón “Consultar” lo podés dejar preparado para más adelante -->
                    <!--
                    <button class="boton-foreach" onclick="location.href='<?= url('/compras/consultar/' . $c['ID']) ?>'">
                        Consultar
                    </button>
                    -->
                </div>
            <?php endforeach; ?>    
    </div>

</body>

</html>