<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verificación de Código</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../Public/css/digitos.css">

</head>

<body>

  <div class="formulario">
    <div class="titulo">Recuperación de Contraseña</div>
    <div class="eslogan">Revise su email e introduzca el código</div>
    <div class="error" style="color:red; margin-top:10px;"></div>
    <form id="codeForm" action="../app/controllers/DigitosController.php" method="POST">
      <div class="code-input">
        <input type="text" name="digit1" maxlength="1" pattern="\d*" inputmode="numeric">
        <input type="text" name="digit2" maxlength="1" pattern="\d*" inputmode="numeric">
        <input type="text" name="digit3" maxlength="1" pattern="\d*" inputmode="numeric">
        <input type="text" name="digit4" maxlength="1" pattern="\d*" inputmode="numeric">
        <input type="text" name="digit5" maxlength="1" pattern="\d*" inputmode="numeric">
        <input type="text" name="digit6" maxlength="1" pattern="\d*" inputmode="numeric">
      </div>

      <button type="submit">Enviar Código</button>
    </form>
  </div>

  <script>
    const inputs = document.querySelectorAll('.code-input input');
    const form = document.getElementById('codeForm');
    const errorDiv = document.querySelector('.error');

    // Auto avance y retroceso
    inputs.forEach((input, index) => {
      input.addEventListener('input', (e) => {
        const value = e.target.value;
        if (value && index < inputs.length - 1) {
          inputs[index + 1].focus();
        }
      });

      input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !input.value && index > 0) {
          inputs[index - 1].focus();
        }
      });
    });

    // Validación del formulario
    form.addEventListener('submit', (e) => {
      const values = Array.from(inputs).map(i => i.value);
      const code = values.join('');

      // Limpiar mensaje previo
      errorDiv.textContent = '';

      if (values.some(v => v === '')) {
        e.preventDefault(); // Evita que se envíe el form
        errorDiv.textContent = 'Por favor, complete los 6 dígitos antes de continuar.';
      } 
      // Si quieres, aquí puedes dejar que se envíe automáticamente el form si todo está correcto
    });
  </script>

</body>

</html>
