<?php
if (session_status() !== PHP_SESSION_ACTIVE) { 
    session_start(); 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Q-Pay</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= url('/css/login.css') ?>">
</head>

<body>
    <div class="formulario">
        <div class="header-verde">
            Q-Pay
        </div>

        <div class="titulo">Bienvenido a Q-Pay</div>
        <div class="eslogan">Expertos en Agilidad</div>

        <div class="error" id="error-container">
            <?php 
                if (isset($_SESSION['error'])) { 
                    echo "⚠️ " . $_SESSION['error']; 
                    unset($_SESSION['error']); 
                } 
            ?>
            <span id="error-msg"></span>
        </div>

        <form id="miFormulario" action="<?= url('/login') ?>" method="POST">
            <input id="correo" name="correo" type="email" placeholder="✉️ Correo electrónico">
            <input id="contrasena" name="contrasena" type="password" placeholder="🔒 Contraseña">
            
            <button type="submit">Iniciar Sesión</button>
        </form>

        <div class="links">
            <a href="<?= url('/registro') ?>">Crear Cuenta</a>
            <a href="<?= url('/recuperacion') ?>">¿Olvidaste tu Contraseña?</a>
        </div>
    </div>

    <script>
    document.getElementById("miFormulario").addEventListener("submit", function(e) {
        const correo = document.getElementById("correo").value.trim();
        const clave = document.getElementById("contrasena").value.trim();
        const errorSpan = document.getElementById("error-msg");
        
        // Limpiar errores previos
        errorSpan.textContent = "";

        if (correo === "" || clave === "") {
            e.preventDefault();
            errorSpan.textContent = "⚠️ Por favor, completa todos los campos.";
        }
    });
    </script>
</body>
</html>