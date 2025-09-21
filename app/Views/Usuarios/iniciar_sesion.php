<?php /** @var string|null $error */ /** @var string|null $ok */ /** @var string $titulo */ ?>
<h1><?= htmlspecialchars($titulo) ?></h1>

<?php if (!empty($ok)): ?>
<p><?= htmlspecialchars($ok) ?></p>
<?php endif; ?>

<?php if (!empty($error)): ?>
<p><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/Usuarios/autenticar">
  <div>
    <label for="correo">Correo</label><br>
    <input id="correo" name="correo" type="email" required autocomplete="username">
  </div>
  <div>
    <label for="contrasena">Contraseña</label><br>
    <input id="contrasena" name="contrasena" type="password" required autocomplete="current-password" minlength="8">
  </div>
  <button type="submit">Entrar</button>
</form>

<p>¿No tenés cuenta? <a href="<?= BASE_URL ?>/Usuarios/registrar">Crear una cuenta</a></p>
