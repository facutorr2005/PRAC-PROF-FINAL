<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Public/css/registro.css">

</head>

<body>
    <div class="contenido">
        <div class="titulo">Registre su Usuario</div>
        <div class="error" id="error-msg"></div>
        <form id="miFormulario" action="../app/controllers/RegistrosController.php" method="post">
            <input id="nombre" type="text" placeholder="Ingrese su Nombre">
            <input id="apellido" type="text" placeholder="Ingrese su Apellido">
            <label for="fechaNacimiento"> Ingrese su Fecha de Nacimiento</label>
            <input type="date" id="fechaNacimiento">
            <input type="number" id="dni" placeholder="Ingrese su DNI">
            <input id="correo" type="email" placeholder="Ingrese su Correo">
            <input type="password" id="contrasena" placeholder="Ingrese Contraseña">
            <input type="password" id="repetircontrasena" placeholder="Confirme Contraseña">
            <button type="submit">Completar Registro</button>
        </form>
        <div class="links">
            <a href="login.php">Ya Tengo Usuario</a>
            <a href="recuperacion.php">Olvide Mi Contraseña</a>
        </div>
    </div>
<script>
document.getElementById("miFormulario").addEventListener("submit", function (e) {
    const nombre = document.getElementById("nombre").value.trim();
    const apellido = document.getElementById("apellido").value.trim();
    const fechaNacimiento = document.getElementById("fechaNacimiento").value.trim();
    const dni = document.getElementById("dni").value.trim();
    const correo = document.getElementById("correo").value.trim();
    const contrasena = document.getElementById("contrasena").value.trim();
    const repetir = document.getElementById("repetircontrasena").value.trim();
    const errorDiv = document.getElementById("error-msg");

    // Limpiar mensajes anteriores
    errorDiv.textContent = "";

    // 1️⃣ Verificar campos vacíos
    if (
        nombre === "" ||
        apellido === "" ||
        fechaNacimiento === "" ||
        dni === "" ||
        correo === "" ||
        contrasena === "" ||
        repetir === ""
    ) {
        e.preventDefault();
        errorDiv.textContent = "⚠️ Por favor, complete todos los campos antes de continuar.";
        return;
    }

    // 2️⃣ Verificar edad mínima de 13 años
    const hoy = new Date();
    const fechaNac = new Date(fechaNacimiento);
    const edad = hoy.getFullYear() - fechaNac.getFullYear();
    const mes = hoy.getMonth() - fechaNac.getMonth();
    const edadReal = mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate()) ? edad - 1 : edad;

    if (edadReal < 13) {
        e.preventDefault();
        errorDiv.textContent = "⚠️ Debes tener al menos 13 años para registrarte.";
        return;
    }

    // 4️⃣ Verificar coincidencia de contraseñas
    if (contrasena !== repetir) {
        e.preventDefault();
        errorDiv.textContent = "⚠️ Las contraseñas no coinciden. Intente nuevamente.";
        return;
    }

    // ✅ Si todo está correcto, el formulario se enviará normalmente
});
</script>


</body>

</html>