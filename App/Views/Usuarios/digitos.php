<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verificación de Código</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= url('css/digitos.css') ?>">

</head>

<body>

  <div class="formulario">
    <h1 class="titulo">Recuperación de Contraseña</h1>
    <p class="eslogan">Revise su email e introduzca el código</p>

    <form id="codeForm" action="<?= url('/codigo') ?>" method="post">
      <div class="code-input">
        <input name= "codigo[]" type="text" maxlength="1" pattern="\d*" inputmode="numeric">
        <input name= "codigo[]" type="text" maxlength="1" pattern="\d*" inputmode="numeric">
        <input name= "codigo[]" type="text" maxlength="1" pattern="\d*" inputmode="numeric">
        <input name= "codigo[]" type="text" maxlength="1" pattern="\d*" inputmode="numeric">
        <input name= "codigo[]" type="text" maxlength="1" pattern="\d*" inputmode="numeric">
        <input name= "codigo[]" type="text" maxlength="1" pattern="\d*" inputmode="numeric">
      </div>
      <button type="submit">Enviar Código</button>
    </form>
  </div>

  <script>
    const inputs = document.querySelectorAll('.code-input input');
    const form   = document.getElementById('codeForm');

      // Auto-avance entre los 6 inputs
      inputs.forEach((input, i) => {
      input.addEventListener('input', (e) => {
        // permitir solo números y 1 dígito
        e.target.value = e.target.value.replace(/\D/g, '').slice(0, 1);
        if (e.target.value && i < inputs.length - 1) {
          inputs[i + 1].focus();
        }
      });

      // Retroceso con Backspace
      input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !input.value && i > 0) {
          inputs[i - 1].focus();
        }
      });
    });

    // Permitir pegar los 6 dígitos de una sola vez
    inputs[0].addEventListener('paste', (e) => {
      const text = (e.clipboardData || window.clipboardData)
        .getData('text')
        .replace(/\D/g, '')
        .slice(0, 6);
      if (!text) return;
      e.preventDefault();
      [...text].forEach((ch, i) => { if (inputs[i]) inputs[i].value = ch; });
      if (text.length === 6) form.requestSubmit();
    });

    // Validación al enviar
    form.addEventListener('submit', (e) => {
      const vacio = [...inputs].some(inp => inp.value === '');
      if (vacio) {
        e.preventDefault();
        alert('Por favor, completá los 6 dígitos antes de continuar.');
      }
    });
</script>


</body>

</html>