<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Q-Pay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Public/css/panel.css">
</head>

<body>
    <div class="encabezado">
        <div class="logo-titulo">
            <img class="logo" src="../Public/imagenes/logo.png" alt="logo">
            <div class="titulo">Q-Pay</div>
        </div>

        <!-- Botón menú hamburguesa (solo visible en móviles) -->
        <button class="menu-toggle" id="menu-toggle" aria-label="Abrir menú">
            ☰
        </button>

        <div class="botones" id="menu">
            <button onclick="location.href='<?= url('/compra') ?>'" class="boton">Iniciar Compra</button>
            <button onclick="location.href='<?= url('/historial') ?>'"class="boton">Historial Compras</button>
            <button onclick="location.href='<?= url('/perfil') ?>'" class="boton">Mi Perfil</button>
            <button onclick="location.href='<?= url('/logout') ?>'" class="boton">Cerrar sesión</button>
        </div>
    </div>

    <div class="contenido">
        <img src="../Public/imagenes/promo-1.png" alt="oferta-1" class="oferta oferta-1">
        <img src="../Public/imagenes/promo-2.png" alt="oferta-2" class="oferta oferta-2">
        <img src="../Public/imagenes/promo-3.png" alt="oferta-3" class="oferta oferta-3">
        <img src="../Public/imagenes/promo-4.png" alt="oferta-4" class="oferta oferta-4">
        <img src="../Public/imagenes/promo-5.png" alt="oferta-5" class="oferta oferta-5">
        <img src="../Public/imagenes/promo-6.png" alt="oferta-6" class="oferta oferta-6">
    </div>

    <div class="pie-pagina">
        <div class="contactos">
            <div class="titulo-pie">Q-Pay</div>
            <div class="info-pie">📧 q-pay@gmail.com</div>
            <div class="info-pie">📞 +33 3 333 3333</div>
            <div class="info-pie">📧 rrhh-pay@gmail.com</div>
        </div>
        <div class="contactos-desarrollo">
            <div class="titulo-pie">Innovative Bytes</div>
            <div class="info-pie">📧 innovativebytes@gmail.com</div>
            <div class="info-pie">📞 +33 3 333 3333</div>
            <div class="info-pie"><a class="enlace-pie" href="https://www.instagram.com/">📷 Instagram</a></div>
        </div>
        <div class="redes-sociales">
            <div class="titulo-pie">Redes Q-Pay</div>
            <div class="info-pie"><a class="enlace-pie" href="https://www.facebook.com/">👍 Facebook</a></div>
            <div class="info-pie"><a class="enlace-pie" href="https://www.youtube.com/">🎬 Youtube</a></div>
            <div class="info-pie"><a class="enlace-pie" href="https://www.instagram.com/">📷 Instagram</a></div>
        </div>
    </div>

    <script>
        // Script para abrir/cerrar el menú hamburguesa
        const toggleBtn = document.getElementById("menu-toggle");
        const menu = document.getElementById("menu");

        toggleBtn.addEventListener("click", () => {
            menu.classList.toggle("activo");
            toggleBtn.classList.toggle("abierto");
        });
    </script>
</body>

</html>