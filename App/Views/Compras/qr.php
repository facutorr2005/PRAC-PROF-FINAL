<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código QR de tu Compra</title>
    <link rel="stylesheet" href="<?= url('/css/qr.css') ?>">
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
</head>
<body>
    <div class="qr-container">
        <h1>¡Gracias por tu compra!</h1>
        <p>Escanea el siguiente código QR para ver el detalle.</p>
        <div id="qrcode"></div>
        <a href="<?= url('/panel') ?>" class="boton">Volver al Panel</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // El ID de la transacción se pasará desde el controlador
            const idTransaccion = "<?php echo $id_transaccion ?? '' ?>";

            if (idTransaccion) {
                new QRCode(document.getElementById("qrcode"), {
                    text: "<?= url('/compras/detalle/') ?>" + idTransaccion,
                    width: 256,
                    height: 256,
                    correctLevel: QRCode.CorrectLevel.H
                });
            }
        });
    </script>
</body>
</html>
