<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperacion de Contraseña</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/recuperacion.css">
</head>
<body>
    <div class="contenido">
        <Div class="titulo">Recuperacion de Contraseña</Div>
        <div class="info">Se le enviara un correo electronico con un codigo de 6 digitos para recuperar la contraseña.</div>
        <form id="miFormulario" action="<?= BASE_URL ?> /Contrasenas/Olvidada" method="post">
            <input id="correo" type="email" placeholder="Introduzca su Correo">
            <button type="submit">Enviar Codigo</button>
        </form>
    </div>
    <script>
    document.getElementById("miFormulario").addEventListener("submit", function(e) {
      e.preventDefault();
      
      const correo = document.getElementById("correo").value.trim();
      
      if (correo === "") {
        alert("Introduzca Un Correo Electronico.");
        return;
      }
    });
    </script>
</body>
</html>