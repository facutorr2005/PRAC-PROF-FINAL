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
  <title>Verificación de Código</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <!-- Tu helper -->
  <link rel="stylesheet" href="<?= url('css/digitos.css') ?>">
</head>

<body>

  <div class="formulario">

    <div class="titulo">Recuperación de Contraseña</div>
    <div class="eslogan">Revise su email e introduzca el código, tiene 10 minutos.</div>

    <!-- Mensaje del servidor -->
    <?php if (!empty($_SESSION['error'])): ?>
      <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['ok'])): ?>
      <div class="ok" style="color:green;"><?= htmlspecialchars($_SESSION['ok']) ?></div>
      <?php unset($_SESSION['ok']); ?>
    <?php endif; ?>

    <div class="error" id="front-error"></div>

    <form id="codeForm" action="<?= url('/codigo') ?>" method="POST">
      <div class="code-input">
        <input name="codigo[]" type="text" maxlength="1" pattern="\d*" inputmode="numeric">
        <input name="codigo[]" type="text" maxlength="1" pattern="\d*" inputmode="numeric">
        <input name="codigo[]" type="text" maxlength="1" pattern="\d*" inputmode="numeric">
        <input name="codigo[]" type="text" maxlength="1" pattern="\d*" inputmode="numeric">
        <input name="codigo[]" type="text" maxlength="1" pattern="\d*" inputmode="numeric">
        <input name="codigo[]" type="text" maxlength="1" pattern="\d*" inputmode="numeric">
      </div>

      <div class="temporizador" id="temporizador">10:00</div>

      <button type="submit">Enviar Código</button>
    </form>
  </div>

<script>
  const inputs = document.querySelectorAll('.code-input input');
  const form   = document.getElementById('codeForm');
  const errorDiv = document.getElementById('front-error');

  // Auto avance / retroceso
  inputs.forEach((input, index) => {

    input.addEventListener('input', (e) => {
      e.target.value = e.target.value.replace(/\D/g, '').slice(0, 1);
      if (e.target.value && index < inputs.length - 1) {
        inputs[index + 1].focus();
      }
    });

    input.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace' && !input.value && index > 0) {
        inputs[index - 1].focus();
      }
    });
  });

  // Permitir pegar los 6 dígitos
  inputs[0].addEventListener('paste', (e) => {
    const text = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
    if (!text) return;

    e.preventDefault();
    [...text].forEach((char, i) => inputs[i].value = char);

    if (text.length === 6) form.requestSubmit();
  });

  // Validación antes de enviar
  form.addEventListener('submit', (e) => {
    const vacio = [...inputs].some(inp => inp.value === '');
    if (vacio) {
      e.preventDefault();
      errorDiv.textContent = "Por favor, complete los 6 dígitos.";
    }
  });

  // Temporizador 10 minutos
  let tiempo = 10 * 60;
  const temporizador = document.getElementById('temporizador');

  const interval = setInterval(() => {
    const min = Math.floor(tiempo / 60);
    const seg = tiempo % 60;

    temporizador.textContent =
      `${min.toString().padStart(2,'0')}:${seg.toString().padStart(2,'0')}`;

    tiempo--;

    if (tiempo < 0) {
      clearInterval(interval);
      window.location.href = "<?= url('/recuperacion') ?>";
    }
  }, 1000);
</script>

</body>

</html>
