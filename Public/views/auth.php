<?php
session_start();
$_SESSION['correo'] = $_POST['correo'];

if($_SESSION['correo'] == 'l@l.com'){
unset($_SESSION['error']);
header('Location: http://localhost/PRAC-PROF-FINAL/public/views/panel.php');
}
else{
    $_SESSION['error'] = 'Usuario o Contraseña incorrectos';
   header('Location: http://localhost/PRAC-PROF-FINAL/public/views/login.php');
}
exit;
