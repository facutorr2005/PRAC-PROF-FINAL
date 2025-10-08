<h2>Restablecer contraseña</h2>
<form method="POST" action="<?= BASE_URL ?>/Contrasenas/enviar_codigo">
  <label for="correo">Correo</label><br>
  <input id="correo" name="correo" type="email" required>
  <button type="submit">Enviar código</button>
</form>
