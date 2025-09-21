<?php /** @var string|null $error */ /** @var string $titulo */ ?>
<h1><?= htmlspecialchars($titulo) ?></h1>

<?php if (!empty($error)): ?>
<p><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/Usuarios/guardar">
  <div>
    <label for="nombre">Nombre</label><br>
    <input id="nombre" name="nombre" type="text" required maxlength="100">
  </div>
  <div>
    <label for="apellido">Apellido</label><br>
    <input id="apellido" name="apellido" type="text" required maxlength="100">
  </div>
  <div>
    <label for="correo">Correo</label><br>
    <input id="correo" name="correo" type="email" required autocomplete="email">
  </div>
  <div>
    <label for="contrasena">Contraseña</label><br>
    <input id="contrasena" name="contrasena" type="password" required autocomplete="new-password" minlength="8">
  </div>
  <div>
    <label for="contrasena2">Repetir contraseña</label><br>
    <input id="contrasena2" name="contrasena2" type="password" required autocomplete="new-password" minlength="8">
  </div>
  <div>
    <label for="fecha_nacimiento">Fecha de nacimiento</label><br>
    <input id="fecha_nacimiento" name="fecha_nacimiento" type="date" required>
  </div>
  <div>
    <label for="dni">DNI</label><br>
    <input id="dni" name="dni" type="text" inputmode="numeric" pattern="\d{7,10}" title="Solo números, 7 a 10 dígitos" required>
  </div>
  <button type="submit">Registrarme</button>
</form>

<p>¿Ya tenés cuenta? <a href="<?= BASE_URL ?>/Usuarios/iniciarSesion">Iniciar sesión</a></p>

