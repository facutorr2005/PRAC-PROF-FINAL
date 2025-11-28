<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Confirmar Eliminación</title>

  <!-- Fuentes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="<?= url('css/confirmareliminacion.css') ?>">
</head>

<body>
  <div class="contenido">
    <div class="titulo">Confirmar Eliminación</div>
    <div class="eslogan">Introduzca su contraseña dos veces para confirmar esta acción</div>

    <!-- Mostrar errores del backend -->
    <?php if (!empty($_SESSION['error'])): ?>
      <p style="color:red; text-align:center;"><?= htmlspecialchars($_SESSION['error']) ?></p>
      <?php $_SESSION['error'] = null; ?>
    <?php endif; ?>

    <form id="formEliminar" action="<?= url('/confirmareliminacion') ?>" method="POST">
      <input id="password" name="contrasena" type="password" placeholder="Introducir Contraseña" required>
      <input id="repetir" name="repetir" type="password" placeholder="Repetir Contraseña" required>
      <button type="submit">Confirmar Eliminación</button>
      <a href="<?= url('/perfil') ?>" style="margin-left:10px;">Cancelar</a>
    </form>
  </div>

  <script>
    document.getElementById("formEliminar").addEventListener("submit", function (e) {
      const pass1 = document.getElementById("password").value.trim();
      const pass2 = document.getElementById("repetir").value.trim();

      if (pass1 === "" || pass2 === "") {
        alert("Por favor, complete ambos campos.");
        e.preventDefault();
        return;
      }

      if (pass1 !== pass2) {
        alert("Las contraseñas no coinciden. Intente nuevamente.");
        e.preventDefault();
      }
    });
  </script>
</body>
</html>
