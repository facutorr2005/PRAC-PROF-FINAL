<?php /** @var string $correo */ /** @var string $nombre */ /** @var string $apellido */ ?>
<h1>Bienvenido</h1>
<p>Estás logueado como <strong><?= htmlspecialchars($nombre . ' ' . $apellido) ?></strong> (<?= htmlspecialchars($correo) ?>).</p>
<p><a href="<?= BASE_URL ?>/Usuarios/cerrarSesion">Cerrar sesión</a></p>

