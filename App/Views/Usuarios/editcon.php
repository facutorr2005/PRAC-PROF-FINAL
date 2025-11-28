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
  <title>Nueva Contraseña</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= url('css/editcon.css') ?>">
</head>
<body>

<div class="contenido">
  <div class="titulo">Recuperación de Contraseña</div>
  <div class="eslogan">Ingrese su nueva contraseña y repítala para confirmar</div>

  <!-- Mensajes desde el servidor, por si el controller los usa -->
  <?php if (!empty($_SESSION['error'])): ?>
      <div class="error"><?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?></div>
      <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <?php if (!empty($_SESSION['ok'])): ?>
      <div class="ok" style="color:green;"><?= htmlspecialchars($_SESSION['ok'], ENT_QUOTES, 'UTF-8') ?></div>
      <?php unset($_SESSION['ok']); ?>
  <?php endif; ?>

  <!-- Mensaje de validación del front -->
  <div class="error" id="error-msg"></div>

  <form id="miFormulario" action="<?= url('/reset') ?>" method="post" novalidate>
      <input id="contrasena" name="contrasena" type="password" placeholder="Nueva Contraseña">
      <input id="repetir"    name="repetir"    type="password" placeholder="Repetir Contraseña">
      <button type="submit">Guardar Contraseña</button>
  </form>
</div>

<script>
  const form = document.getElementById('miFormulario');

  form.addEventListener('submit', function (e) {
    const p1 = document.getElementById('contrasena').value.trim();
    const p2 = document.getElementById('repetir').value.trim();
    const errorDiv = document.getElementById('error-msg');

    errorDiv.textContent = '';
    errorDiv.style.color = 'red';

    if (!p1 || !p2) {
      e.preventDefault();
      errorDiv.textContent = '⚠️ Todos los campos son obligatorios';
      return;
    }

    if (p1 !== p2) {
      e.preventDefault();
      errorDiv.textContent = 'Las contraseñas no coinciden. Intente nuevamente.';
      return;
    }

    // Si todo está OK, no hacemos preventDefault => se envía a /reset
  });
</script>

</body>
</html>
