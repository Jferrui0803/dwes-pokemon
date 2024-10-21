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
    header('Location: ./');
    exit;
}

// Verificar que los datos del formulario estén presentes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'], $_POST['name'], $_POST['type'], $_POST['ability'], $_POST['hp'], $_POST['attack'], $_POST['defense'])) {
        $id = (int)$_POST['id'];
        $name = trim($_POST['name']);
        $type = trim($_POST['type']);
        $ability = trim($_POST['ability']);
        $hp = (int)$_POST['hp'];
        $attack = (int)$_POST['attack'];
        $defense = (int)$_POST['defense'];

        // Preparar la consulta de actualización
        $sql = 'UPDATE pokemon SET name = :name, type = :type, ability = :ability, hp = :hp, attack = :attack, defense = :defense WHERE id = :id';
        $statement = $connection->prepare($sql);

        // Vincular valores
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':type', $type, PDO::PARAM_STR);
        $statement->bindValue(':ability', $ability, PDO::PARAM_STR);
        $statement->bindValue(':hp', $hp, PDO::PARAM_INT);
        $statement->bindValue(':attack', $attack, PDO::PARAM_INT);
        $statement->bindValue(':defense', $defense, PDO::PARAM_INT);

        try {
            // Ejecutar la consulta
            $statement->execute();
            header('Location: ./?op=editpokemon&result=success');
        } catch (PDOException $e) {
            header('Location: ./?op=editpokemon&result=updatefail');
        }
    } else {
        header('Location: ./?op=editpokemon&result=missingdata');
    }
} else {
    header('Location: ./');
}

$connection = null;
?>
