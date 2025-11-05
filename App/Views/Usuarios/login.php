<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Q-Pay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('css/login.css') ?>">

</head>

<<div class="formulario">
    <div class="titulo">Bienvenido a Q-Pay</div>
    <div class="eslogan">Expertos en Agilidad</div>

    <!-- Mostrar errores de PHP si existen -->
    <div class="error">
        <?php if(isset($_SESSION['error'])) { echo $_SESSION['error']; } ?>
    </div>

    <!-- Mensaje de error para JavaScript -->
    <div class="error" id="error-msg"></div>

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

    // Limpiar error anterior
    errorDiv.textContent = "";

    // Validación
    if (correo === "" || clave === "") {
        e.preventDefault(); // Evita que el formulario se envíe
        errorDiv.textContent = "⚠️ Todos los campos son obligatorios.";
        errorDiv.style.color = "red"; // Corregido: "rojo" no es válido en CSS
        return;
    }
});
</script>

</body>
</html>