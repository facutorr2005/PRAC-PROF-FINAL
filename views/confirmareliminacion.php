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
  <link rel="stylesheet" href="../Public/css/confirmareliminacion.css">
</head>

<body>

  <div class="contenido">
    <div class="titulo">Confirmar Eliminación</div>
    <div class="eslogan">Introduzca su contraseña para confirmar esta acción</div>

    <!-- Div para mostrar errores -->
    <div class="error" style="color:red; margin-bottom:10px;"></div>

    <form id="formEliminar" action="../app/controllers/EliminacionesController.php" method="POST">
      <input id="password" name="contrasena" type="password" placeholder="Introducir Contraseña">
      <input id="repetir" type="password" placeholder="Repetir Contraseña">
      <button type="submit">Confirmar Eliminación</button>
    </form>

    <script>
      const form = document.getElementById("formEliminar");
      const errorDiv = document.querySelector(".error");

      form.addEventListener("submit", function (e) {
        const pass1 = document.getElementById("password").value.trim();
        const pass2 = document.getElementById("repetir").value.trim();

        // Limpiar mensajes previos
        errorDiv.textContent = "";

        // Validar campos vacíos
        if (pass1 === "" || pass2 === "") {
          e.preventDefault();
          errorDiv.textContent = "Por favor, complete ambos campos.";
          return;
        }

        // Validar coincidencia de contraseñas
        if (pass1 !== pass2) {
          e.preventDefault();
          errorDiv.textContent = "Las contraseñas no coinciden. Intente nuevamente.";
          return;
        }

        // Si todo está correcto, el formulario se envía normalmente
      });
    </script>

</body>

</html>
