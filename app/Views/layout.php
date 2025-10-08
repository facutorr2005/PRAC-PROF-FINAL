<?php
// app/Views/layout.php
?><!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title><?= isset($titulo) ? htmlspecialchars($titulo) : 'Qpay' ?></title>
</head>
<body>
<?php
// Incluimos el contenido que el controlador pasó en $content
if (isset($content) && file_exists($content)) {
    include $content;
} else {
    echo "<p>Vista no encontrada: <code>" . htmlspecialchars((string)($content ?? '')) . "</code></p>";
}
?>
</body>
</html>
