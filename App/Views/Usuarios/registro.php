<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('css/registro.css') ?>">

</head>

<body>
    <div class="contenido">
        <div class="titulo">Registre su Usuario</div>
        <div class="error">
            <?php if(isset($_SESSION['error'])) { echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); } ?>
            <?php if(isset($_SESSION['ok']))    { echo '<span style="color:green">'.htmlspecialchars($_SESSION['ok']).'</span>'; unset($_SESSION['ok']); } ?>
        </div>
    <form id="miFormulario" action="<?= url('/registro') ?>" method="POST">
        <input id="nombre"      name="nombre"      type="text"     placeholder="Ingrese su Nombre">
        <input id="apellido"    name="apellido"    type="text"     placeholder="Ingrese su Apellido">

        <label for="fecha_nac">Ingrese su Fecha de Nacimiento</label>
        <input id="fecha_nac"   name="fecha_nac"   type="date"     placeholder="Ingrese su Fecha de Nacimiento">

        <input id="dni"         name="dni"         type="text"     placeholder="Ingrese su DNI">
        <input id="correo"      name="correo"      type="email"    placeholder="Ingrese su Correo">
        <input id="contrasena"  name="contrasena"  type="password" placeholder="Ingrese su Contraseña">
        <input id="contrasena2" name="contrasena2" type="password" placeholder="Confirme Contraseña">

        <button type="submit">Completar Registro</button>
    </form>

        <div class="links">
            <a href="<?= url('/login') ?>">Ya Tengo Usuario</a>
            <a href="<?= url('/recuperacion') ?>">Olvide Mi Contraseña</a>
        </div>
    </div>
    <script>
        document.getElementById("miFormulario").addEventListener("submit", function (e) {
            const nombre = document.getElementById("nombre").value.trim();
            const apellido = document.getElementById("apellido").value.trim();
            const fechaNacimiento = document.getElementById("fecha_nac").value.trim();
            const dni = document.getElementById("dni").value.trim();
            const correo = document.getElementById("correo").value.trim();
            const contrasena = document.getElementById("contrasena").value.trim();
            const repetir = document.getElementById("contrasena2").value.trim();

            // Verificar que todos los campos estén completos
            if (
                nombre === "" ||
                apellido === "" ||
                fechaNacimiento === "" ||
                dni === "" ||
                correo === "" ||
                contrasena === "" ||
                repetir === ""
            ) {
                alert("Por favor, complete todos los campos antes de continuar.");
                e.preventDefault();
                return;
            }

            // Verificar edad mínima de 13 años
            const hoy = new Date();
            const fechaNac = new Date(fechaNacimiento);
            const edad = hoy.getFullYear() - fechaNac.getFullYear();
            const mes = hoy.getMonth() - fechaNac.getMonth();
            const edadReal = mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate()) ? edad - 1 : edad;

            if (edadReal < 13) {
                alert("Debes tener al menos 13 años para registrarte.");
                e.preventDefault();
                return;
            }

            // Verificar que el DNI tenga exactamente 8 dígitos
            const dniRegex = /^[0-9]{8}$/;
            if (!dniRegex.test(dni)) {
                alert("El DNI debe contener exactamente 8 dígitos numéricos.");
                e.preventDefault();
                return;
            }

            // Verificar que las contraseñas coincidan
            if (contrasena !== repetir) {
                alert("Las contraseñas no coinciden. Intente nuevamente.");
                e.preventDefault();
                return;
            }

            // Si todo está correcto, el formulario se enviará normalmente por POST
        });
    </script>

</body>

</html>