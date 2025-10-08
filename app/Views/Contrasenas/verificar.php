<h2>Verificar código</h2>
<form method="POST" action="<?= BASE_URL ?>/Contrasenas/verificar">
  <label for="correo">Correo</label><br>
  <input id="correo" name="correo" type="email" required>

  <label for="codigo">Código (6 dígitos)</label><br>
  <input id="codigo" name="codigo" type="text" pattern="[0-9]{6}" maxlength="6" required>

  <button type="submit">Verificar</button>
</form>
