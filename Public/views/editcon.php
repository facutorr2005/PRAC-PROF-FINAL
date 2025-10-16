<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nueva Contraseña</title>

  <!-- Fuentes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/editcon.css">
</head>
<body>

<div class="contenido">
  <div class="titulo">Recuperación de Contraseña</div>
  <div class="eslogan">Ingrese su nueva contraseña y repítala para confirmar</div>

    <form id="miFormulario">
      <input id="contrasena" type="password" placeholder="Nueva Contraseña">
      <input id="repetir" type="password" placeholder="Repetir Contraseña">
      <button type="submit">Guardar Contraseña</button>
    </form>
  </div>

  <script>
    document.getElementById("miFormulario").addEventListener("submit", function(e) {
      e.preventDefault();

      const pass1 = document.getElementById("contrasena").value.trim();
      const pass2 = document.getElementById("repetir").value.trim();

      if (pass1 === "" || pass2 === "") {
        alert("Por favor, complete ambos campos.");
        return;
      }

      if (pass1 !== pass2) {
        alert("Las contraseñas no coinciden. Intente nuevamente.");
        return;
      }

      alert("Contraseña actualizada correctamente.");
      // Aquí podrías redirigir o enviar al servidor con fetch()
      // Ejemplo: window.location.href = "login.html";
    });
  </script>

</body>
</html>
