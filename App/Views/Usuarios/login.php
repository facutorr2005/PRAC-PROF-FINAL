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

<body>
    <div class="formulario">
        <div class="titulo">
          Bienvenido a Q-Pay!
        </div>
        <div class="eslogan">
          Expertos en Agilidad
        </div>
        <div class="error"> 
            <?php if(isset($_SESSION['error'])) { ?>
           <?php echo $_SESSION['error']; }?>
        </div>
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
   //   e.preventDefault();
      
      const correo = document.getElementById("correo").value.trim();
      const clave = document.getElementById("contrasena").value.trim();
      
      if (correo === "" || clave === "") {
        alert("Todos los campos son obligatorios.");
        return;
      }
    });
    </script>
</body>
</html>