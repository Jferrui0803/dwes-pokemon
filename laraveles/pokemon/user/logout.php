<?php
session_start();
unset($_SESSION['user']);

// Cambié el URL de redirección para usar `./` en lugar de `..`
$url = '..?op=logout&result=ok';
header('Location: ' . $url);
exit(); // Siempre es buena práctica usar exit después de header()
?>
