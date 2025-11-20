<?php
// Mantener sesiones como en nuestro proyecto
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

    <!-- Fuentes (igual que tu amigo) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Nuestro helper url() apuntando al CSS correcto -->
    <link rel="stylesheet" href="<?= url('/css/login.css') ?>">
</head>

<body>
    <div class="formulario">
        <div class="titulo">
            Bienvenido a Q-Pay
        </div>
        <div class="eslogan">
            Expertos en Agilidad
        </div>

        <!-- Mostrar errores de PHP si existen -->
        <div class="error">
            <?php if (isset($_SESSION['error'])) { echo $_SESSION['error']; } ?>
        </div>

        <!-- Mensaje de error para JavaScript -->
        <div class="error" id="error-msg"></div>

        <!-- Acción usando nuestro router -->
        <form id="miFormulario" action="<?= url('/login') ?>" method="POST">
            <input id="correo" name="correo" type="email" placeholder="Introduzca su Correo">
            <input id="contrasena" name="contrasena" type="password" placeholder="Introduzca su Contraseña">
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
        const errorDiv = document.getElementById("error-msg");

        // Limpiar mensaje anterior
        errorDiv.textContent = "";

        // Validación
        if (correo === "" || clave === "") {
            e.preventDefault(); // Evita el envío del formulario
            errorDiv.textContent = "⚠️ Todos los campos son obligatorios.";
            errorDiv.style.color = "red";
            return;
        }
    });
    </script>
</body>
</html>
