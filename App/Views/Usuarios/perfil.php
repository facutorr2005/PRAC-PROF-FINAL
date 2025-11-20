<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Q-Pay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('css/perfil.css') ?>?v=2">

</head>

<body>
    <div class="encabezado">
        <div class="logoTitulo">
            <img class="logo" src="../Public/imagenes/logo.png" alt="logo">
            <div class="titulo">Q-Pay</div>
        </div>

        <div class="botones">
            <button onclick="location.href='<?= url('/panel') ?>'" class="boton">Volver al inicio</button>
            <button onclick="location.href='<?= url('/confirmareliminacion') ?>'" class="boton eliminarBoton">Eliminar cuenta</button>
        </div>

        </div>
    </div>

    <div class="contenidoPerfil">
        <div class="perfilTitulo">Mi Perfil</div>
        <form action="<?= url('/perfil') ?>" method="POST" class="formularioPerfil" id="formPerfil">
            <div class="grupoCampo">
                <div class="errorCampo" id="errorCorreo"></div>
                <div class="etiquetaCampo">Correo:</div>
                <input type="email" name="correo" value="juanperez@gmail.com" class="entradaCampo">
            </div>

            <div class="grupoCampo">
                <div class="errorCampo" id="errorNombre"></div>
                <div class="etiquetaCampo">Nombre:</div>
                <input type="text" name="nombre" value="Juan" class="entradaCampo">
            </div>

            <div class="grupoCampo">
                <div class="errorCampo" id="errorApellido"></div>
                <div class="etiquetaCampo">Apellido:</div>
                <input type="text" name="apellido" value="Pérez" class="entradaCampo">
            </div>

            <div class="grupoCampo">
                <div class="errorCampo" id="errorFechaNacimiento"></div>
                <div class="etiquetaCampo">Fecha de nacimiento:</div>
                <input type="date" name="fechaNacimiento" value="1990-01-01" class="entradaCampo">
            </div>

            <div class="grupoCampo">
                <div class="errorCampo" id="errorDni"></div>
                <div class="etiquetaCampo">DNI:</div>
                <input type="text" name="dni" value="12345678" class="entradaCampo">
            </div>

            <div class="grupoCampo">
                <div class="errorCampo" id="errorContrasena"></div>
                <div class="etiquetaCampo">Contraseña:</div>
                <input type="password" name="contrasena" placeholder="Dejar en blanco para no cambiar" class="entradaCampo">
            </div>

            <div class="grupoCampo">
                <button type="submit" name="guardar" class="boton guardarBoton">Guardar Cambios</button>
            </div>
        </form>
    </div>

    <script>
        const formPerfil = document.getElementById('formPerfil');

        formPerfil.addEventListener('submit', function(e){
            e.preventDefault(); // evitamos enviar el formulario si hay errores

            let campos = ['correo','nombre','apellido','fechaNacimiento','dni','contrasena'];
            let todoValido = true;

            campos.forEach(function(campo){
                const input = formPerfil.querySelector(`[name=${campo}]`);
                const errorDiv = document.getElementById(`error${campo.charAt(0).toUpperCase() + campo.slice(1)}`);
                
                if(input.value.trim() === '' && campo !== 'contrasena'){ 
                    errorDiv.textContent = 'Este campo es obligatorio';
                    todoValido = false;
                } else {
                    errorDiv.textContent = '';
                }
            });

            if(todoValido){
                formPerfil.submit(); // si todo está lleno, enviamos el formulario
            }
        });
    </script>
</body>
</html>
