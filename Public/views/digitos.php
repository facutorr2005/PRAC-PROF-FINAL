<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verificación de Código</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/digitos.css">

</head>

<body>

  <div class="formulario">
    <h1 class="titulo">Recuperación de Contraseña</h1>
    <p class="eslogan">Revise su email e introduzca el código</p>

    <form id="codeForm">
      <div class="code-input">
        <input type="text" maxlength="1" pattern="\d*" inputmode="numeric">
        <input type="text" maxlength="1" pattern="\d*" inputmode="numeric">
        <input type="text" maxlength="1" pattern="\d*" inputmode="numeric">
        <input type="text" maxlength="1" pattern="\d*" inputmode="numeric">
        <input type="text" maxlength="1" pattern="\d*" inputmode="numeric">
        <input type="text" maxlength="1" pattern="\d*" inputmode="numeric">
      </div>
      <button type="submit">Enviar Código</button>
    </form>
  </div>

  <script>
    const inputs = document.querySelectorAll('.code-input input');
    const form = document.getElementById('codeForm');

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
      e.preventDefault();
      const values = Array.from(inputs).map(i => i.value);
      const code = values.join('');

      // 🔧 Corrección: verificar si alguno está vacío
      if (values.some(v => v === '')) {
        alert('Por favor, complete los 6 dígitos antes de continuar.');
      } else {
        alert('Código ingresado: ' + code);
        // aquí podrías enviar el código al backend con fetch()
      }
    });
  </script>

</body>

</html>