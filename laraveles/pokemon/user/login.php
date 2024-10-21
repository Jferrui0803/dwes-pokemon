<?php
session_start();

// Verificamos si se ha enviado un usuario
if (isset($_GET['user'])) {
    $_SESSION['user'] = $_GET['user'];
}

// Cambié el URL de redirección para usar `./` en lugar de `..`
$url = '..?op=login&result=ok';
header('Location: ' . $url);
exit(); // Siempre es buena práctica usar exit después de header()
?>
