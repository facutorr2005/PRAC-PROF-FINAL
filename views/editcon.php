<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nueva Contraseña</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../Public/css/editcon.css">
</head>
<body>

<div class="contenido">
  <div class="titulo">Recuperación de Contraseña</div>
  <div class="eslogan">Ingrese su nueva contraseña y repítala para confirmar</div>
        <div class="error" id="error-msg"></div>
    <form id="miFormulario">
      <input id="contrasena" type="password" placeholder="Nueva Contraseña">
      <input id="repetir" type="password" placeholder="Repetir Contraseña">
      <button type="submit">Guardar Contraseña</button>
    </form>
  </div>

  <script>
    document.getElementById("miFormulario").addEventListener("submit", function(e) {
      const primerclave = document.getElementById("contrasena").value.trim();
      const segundaclave = document.getElementById("repetir").value.trim();
      const errorDiv = document.getElementById("error-msg");

      errorDiv.textContent = "";

      if (primerclave === "" || segundaclave === ""){
        e.preventDefault();
        errorDiv.textContent = "⚠️ Todos los campos son obligatorios"
        errorDiv.style.color = "red";
        return;
      }

      if (primerclave !== segundaclave) {
        e.preventDefault();
        errorDiv.textContent = "Las contraseñas no coinciden. Intente nuevamente.";
        errorDiv.style.color = "red";
        return;
      }
    });
  </script>

</body>
</html>
