<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header('Location: ./');
    exit;
}

// Conexión a la base de datos
try {
    $connection = new \PDO(
        'mysql:host=localhost;dbname=pokemons',
        'root',
        'FERNANDO',
        array(
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8'
        )
    );
} catch (PDOException $e) {
    header('Location: ../');
    exit;
}

// Verificar si se ha pasado un ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Preparar la consulta para eliminar el Pokémon
    $sql = 'DELETE FROM pokemon WHERE id = :id';
    $sentence = $connection->prepare($sql);
    $sentence->bindValue(':id', $id, PDO::PARAM_INT);

    try {
        // Ejecutar la consulta
        $sentence->execute();
        $url = './?op=destroypokemon&result=success';
    } catch (PDOException $e) {
        $url = './?op=destroypokemon&result=error';
    }
} else {
    $url = './?op=destroypokemon&result=noid';
}

// Redirigir después de la eliminación
header('Location: ' . $url);
exit;
?>
