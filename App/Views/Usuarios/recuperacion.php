<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Recuperación de Contraseña</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= url('/css/recuperacion.css') ?>">
</head>
<body>
  <div class="contenido">
    <div class="titulo">Recuperación de Contraseña</div>
    <div class="info">Se le enviará un correo electrónico con un código de 6 dígitos para recuperar la contraseña.</div>

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['ok'])): ?>
      <div class="ok" style="color:green;"><?= htmlspecialchars($_SESSION['ok']); unset($_SESSION['ok']); ?></div>
    <?php endif; ?>

    <form id="miFormulario" action="<?= url('/recuperacion') ?>" method="post" novalidate>
      <input id="correo" name="correo" type="email" placeholder="Introduzca su Correo" required>
      <button type="submit">Enviar Código</button>
    </form>
  </div>

  <script>
    document.getElementById("miFormulario").addEventListener("submit", function(e) {
      const correo = document.getElementById("correo").value.trim();
      if (correo === "") {
        e.preventDefault(); // solo bloqueo si está vacío
        alert("Introduzca un correo electrónico.");
      }
      // si está OK, NO hacemos preventDefault y el form se envía normalmente
    });
  </script>
</body>
</html>
