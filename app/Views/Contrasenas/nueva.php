<h2>Nueva contraseña</h2>
<form method="POST" action="<?= BASE_URL ?>/Contrasenas/actualizar">
  <label for="contrasena">Nueva contraseña</label><br>
  <input id="contrasena" name="contrasena" type="password" minlength="8" required>

  <label for="contrasena2">Repetir contraseña</label><br>
  <input id="contrasena2" name="contrasena2" type="password" minlength="8" required>

  <button type="submit">Guardar</button>
</form>
