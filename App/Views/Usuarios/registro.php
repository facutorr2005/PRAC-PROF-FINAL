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
    <title>Registro</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= url('/css/registro.css') ?>">
</head>

<body>
    <div class="contenido">
        <div class="titulo">Registre su Usuario</div>

        <div class="error">
            <?php
            if (isset($_SESSION['error'])) {
                echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8');
                unset($_SESSION['error']);
            } elseif (isset($_SESSION['ok'])) {
                echo '<span style="color:green">' . htmlspecialchars($_SESSION['ok'], ENT_QUOTES, 'UTF-8') . '</span>';
                unset($_SESSION['ok']);
            }
            ?>
        </div>

        <div class="error" id="error-msg"></div>

        <form id="miFormulario" action="<?= url('/registro') ?>" method="POST">
            <input id="nombre"      name="nombre"      type="text"     placeholder="Ingrese su Nombre">
            <input id="apellido"    name="apellido"    type="text"     placeholder="Ingrese su Apellido">

            <label for="fechaNacimiento">Ingrese su Fecha de Nacimiento</label>
            <input type="date" id="fechaNacimiento" name="fecha_nac">

            <input id="dni"         name="dni"         type="text"     placeholder="Ingrese su DNI">
            <input id="correo"      name="correo"      type="email"    placeholder="Ingrese su Correo">
            <input id="contrasena"  name="contrasena"  type="password" placeholder="Ingrese su Contraseña">
            <input id="repetircontrasena" name="contrasena2" type="password" placeholder="Confirme Contraseña">

            <button type="submit">Completar Registro</button>
        </form>

        <div class="links">
            <a href="<?= url('/login') ?>">Ya Tengo Usuario</a>
            <a href="<?= url('/recuperacion') ?>">Olvidé Mi Contraseña</a>
        </div>
    </div>

    <script>
    document.getElementById("miFormulario").addEventListener("submit", function (e) {
        const nombre          = document.getElementById("nombre").value.trim();
        const apellido        = document.getElementById("apellido").value.trim();
        const fechaNacimiento = document.getElementById("fechaNacimiento").value.trim();
        const dni             = document.getElementById("dni").value.trim();
        const correo          = document.getElementById("correo").value.trim();
        const contrasena      = document.getElementById("contrasena").value.trim();
        const repetir         = document.getElementById("repetircontrasena").value.trim();
        const errorDiv        = document.getElementById("error-msg");

        errorDiv.textContent = "";

        // 1️⃣ Validación de campos vacíos
        if (nombre === "" || apellido === "" || fechaNacimiento === "" || dni === "" || correo === "" || contrasena === "" || repetir === "") {
            e.preventDefault();
            errorDiv.textContent = "⚠️ Por favor, complete todos los campos.";
            return;
        }

        // 2️⃣ Validación de Edad (Mínimo 13 años)
        const hoy = new Date();
        const fechaNac = new Date(fechaNacimiento);
        let edad = hoy.getFullYear() - fechaNac.getFullYear();
        const mes = hoy.getMonth() - fechaNac.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) {
            edad--;
        }
        if (edad < 13) {
            e.preventDefault();
            errorDiv.textContent = "⚠️ Debes tener al menos 13 años para registrarte.";
            return;
        }

        // 3️⃣ DNI: Mínimo 6 dígitos numéricos (Modificado)
        const dniRegex = /^[0-9]{6,}$/; 
        if (!dniRegex.test(dni)) {
            e.preventDefault();
            errorDiv.textContent = "⚠️ El DNI debe contener al menos 6 dígitos numéricos.";
            return;
        }

        // 4️⃣ Formato de correo
        const correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!correoRegex.test(correo)) {
            e.preventDefault();
            errorDiv.textContent = "⚠️ Ingrese un correo electrónico válido.";
            return;
        }

        // 5️⃣ Coincidencia de contraseñas
        if (contrasena !== repetir) {
            e.preventDefault();
            errorDiv.textContent = "⚠️ Las contraseñas no coinciden.";
            return;
        }
    });
    </script>
</body>
</html>