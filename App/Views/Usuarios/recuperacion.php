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
  <title>Recuperación de Contraseña</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <!-- Ruta usando nuestro helper -->
  <link rel="stylesheet" href="<?= url('/css/recuperacion.css') ?>">
</head>
<body>
  <div class="contenido">
    <div class="titulo">Recuperación de Contraseña</div>
    <div class="info">
      Se le enviará un correo electrónico con un código de 6 dígitos para recuperar la contraseña.
    </div>

    <!-- Mensajes del servidor (PHP) -->
    <?php if (!empty($_SESSION['error'])): ?>
      <div class="error">
        <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
      </div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['ok'])): ?>
      <div class="ok" style="color:green;">
        <?= htmlspecialchars($_SESSION['ok'], ENT_QUOTES, 'UTF-8') ?>
      </div>
      <?php unset($_SESSION['ok']); ?>
    <?php endif; ?>

    <!-- Mensajes de validación del navegador -->
    <div class="error" id="error-msg"></div>

    <!-- Action apuntando a nuestro router -->
    <form id="miFormulario" action="<?= url('/recuperacion') ?>" method="post" novalidate>
      <input id="correo" name="correo" type="email" placeholder="Introduzca su Correo">
      <button type="submit">Enviar Código</button>
    </form>
  </div>

  <script>
  document.getElementById("miFormulario").addEventListener("submit", function(e) {
    const correo   = document.getElementById("correo").value.trim();
    const errorDiv = document.getElementById("error-msg");

    // Limpiar mensaje anterior
    errorDiv.textContent = "";

    // Validar vacío
    if (correo === "") {
      e.preventDefault();
      errorDiv.textContent = "Introduzca un correo electrónico.";
      return;
    }

    // Validar formato simple de correo
    const correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!correoRegex.test(correo)) {
      e.preventDefault();
      errorDiv.textContent = "Ingrese un correo electrónico válido.";
      return;
    }

    // Si todo está bien, el formulario se envía normalmente
  });
  </script>
</body>
</html>
