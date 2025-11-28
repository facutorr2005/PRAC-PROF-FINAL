<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Q-Pay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('css/perfil.css') ?>?v=3">
</head>

<body>
    <?php if (!empty($_SESSION['error'])): ?>
        <div style="background-color: #ffdddd; color: #a00; padding: 10px; text-align: center; border-bottom: 2px solid #f00;">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['ok'])): ?>
        <div style="background-color: #ddffdd; color: #0a0; padding: 10px; text-align: center; border-bottom: 2px solid #0f0;">
            <?= $_SESSION['ok']; unset($_SESSION['ok']); ?>
        </div>
    <?php endif; ?>

    <div class="encabezado">
        <div class="logoTitulo">
            <img class="logo" src="<?= url('imagenes/logo.png') ?>" alt="logo">
            <div class="titulo">Q-Pay</div>
        </div>

        <div class="botones">
            <button onclick="location.href='<?= url('/panel') ?>'" class="boton">Volver al inicio</button>
            <button onclick="location.href='<?= url('/confirmareliminacion') ?>'" class="boton eliminarBoton">Eliminar cuenta</button>
        </div>
    </div>

    <div class="contenidoPerfil">
        <div class="perfilTitulo">Mi Perfil</div>

        <div class="formularioPerfil">
            
            <div class="grupoCampo">
                <div class="etiquetaCampo">Correo (No modificable):</div>
                <input type="email" value="<?= htmlspecialchars($user['Email'] ?? '') ?>" class="entradaCampo bloqueado" disabled>
            </div>

            <div class="grupoCampo">
                <div class="etiquetaCampo">Nombre:</div>
                <input type="text" value="<?= htmlspecialchars($user['Nombre'] ?? '') ?>" class="entradaCampo bloqueado" disabled>
            </div>

            <div class="grupoCampo">
                <div class="etiquetaCampo">Apellido:</div>
                <input type="text" value="<?= htmlspecialchars($user['Apellido'] ?? '') ?>" class="entradaCampo bloqueado" disabled>
            </div>
            <div class="grupoCampo">
                <div class="etiquetaCampo">Fecha de nacimiento:</div>
                    <input type="date" 
                       value="<?= !empty($user['FechaNacimiento']) ? date('Y-m-d', strtotime($user['FechaNacimiento'])) : '' ?>" 
                       class="entradaCampo bloqueado" 
                       disabled>
            </div>

            <div class="grupoCampo">
                <div class="etiquetaCampo">DNI:</div>
                <input type="text" 
                       value="<?= htmlspecialchars($user['DNI'] ?? $user['Dni'] ?? '') ?>" 
                       class="entradaCampo bloqueado" 
                       disabled>
            </div>

        <hr style="border: 1px dashed #ccc; margin: 10px 0;">

        <div class="perfilTitulo" style="font-size: 1.2rem; margin-top:0;">Seguridad</div>

        <form action="<?= url('/perfil/cambiar-password') ?>" method="POST" class="formularioPerfil" id="formPass">
            
            <div class="grupoCampo">
                <div class="errorCampo" id="errorActual"></div>
                <div class="etiquetaCampo">Contraseña ACTUAL (Requerido):</div>
                <input type="password" name="pass_actual" placeholder="Tu contraseña actual" class="entradaCampo">
            </div>

            <div class="grupoCampo">
                <div class="errorCampo" id="errorNueva"></div>
                <div class="etiquetaCampo">NUEVA Contraseña:</div>
                <input type="password" name="pass_nueva" placeholder="Mínimo 6 caracteres" class="entradaCampo">
            </div>

            <div class="grupoCampo">
                <div class="errorCampo" id="errorRepetir"></div>
                <div class="etiquetaCampo">Repetir NUEVA Contraseña:</div>
                <input type="password" name="pass_repetir" placeholder="Repítela para confirmar" class="entradaCampo">
            </div>

            <div class="grupoCampo">
                <button type="submit" class="boton guardarBoton">Actualizar Contraseña</button>
            </div>
        </form>
    </div>

    <script>
        const formPass = document.getElementById('formPass');

        formPass.addEventListener('submit', function(e){
            let todoValido = true;

            // Validar Actual
            const actual = formPass.querySelector('[name=pass_actual]');
            const errActual = document.getElementById('errorActual');
            if(actual.value.trim() === ''){
                errActual.textContent = 'Debes ingresar tu contraseña actual.';
                todoValido = false;
            } else { errActual.textContent = ''; }

            // Validar Nueva
            const nueva = formPass.querySelector('[name=pass_nueva]');
            const errNueva = document.getElementById('errorNueva');
            if(nueva.value.trim().length < 6){
                errNueva.textContent = 'La nueva contraseña debe tener al menos 6 caracteres.';
                todoValido = false;
            } else { errNueva.textContent = ''; }

            // Validar Repetir
            const repetir = formPass.querySelector('[name=pass_repetir]');
            const errRepetir = document.getElementById('errorRepetir');
            if(nueva.value !== repetir.value){
                errRepetir.textContent = 'Las contraseñas nuevas no coinciden.';
                todoValido = false;
            } else { errRepetir.textContent = ''; }

            if(!todoValido){
                e.preventDefault();
            }
        });
    </script>
</body>
</html>