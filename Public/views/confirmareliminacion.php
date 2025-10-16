<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirmar Eliminación</title>

  <!-- Fuentes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/confirmareliminacion.css">
</head>

<body>

  <div class="contenido">
    <div class="titulo">Confirmar Eliminación</div>
    <div class="eslogan">Introduzca su contraseña para confirmar esta acción</div>

    <form id="formEliminar" action="" method="POST">
      <input id="password" name="contrasena" type="password" placeholder="Introducir Contraseña">
      <input id="repetir" type="password" placeholder="Repetir Contraseña">
      <button type="submit">Confirmar Eliminación</button>
    </form>

    <script>
      document.getElementById("formEliminar").addEventListener("submit", function (e) {
        const pass1 = document.getElementById("password").value.trim();
        const pass2 = document.getElementById("repetir").value.trim();

        if (pass1 === "" || pass2 === "") {
          alert("Por favor, complete ambos campos.");
          e.preventDefault(); // Evita que se envíe si falta algo
          return;
        }

        if (pass1 !== pass2) {
          alert("Las contraseñas no coinciden. Intente nuevamente.");
          e.preventDefault(); // Detiene el envío
          return;
        }
      });
    </script>

</body>

</html>