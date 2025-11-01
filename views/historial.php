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

        <div class="logo-titulo">
            <img class="logo" src="https://images.unsplash.com/photo-1592194996308-7b43878e84a6" alt="logo">
            <div class="titulo">
                Q-Pay
            </div>
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
            <?php
            $compras = [
                (object)["Sucursal"=>"La Reina", "Direccion"=>"San Martin 1111", "Fecha"=>"14/07/25", "Precio"=>152379],
                (object)["Sucursal"=>"La Gallega", "Direccion"=>"9 De Julio 832", "Fecha"=>"20/07/25", "Precio"=>20345]
            ]; 
            ?>
            <?php foreach ($compras as $c): ?>
                <div>
                    <?= $c->Sucursal ?> -
                    <?= $c->Direccion ?> -
                    <?= $c->Fecha ?> - Total: 
                    <?= $c->Precio ?>
                    <button class="boton-foreach" onclick="location.href='<?= BASE_URL ?>/compras/consultar/<?= $c->Id ?>'">
                        Consultar
                    </button>
                </div>                              
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>
