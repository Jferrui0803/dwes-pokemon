<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: .');
    exit;
}

// Conectar a la base de datos
try {
    $connection = new PDO(
        'mysql:host=localhost;dbname=pokemons',
        'root',
        'FERNANDO',
        array(
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8'
        )
    );
} catch (PDOException $e) {
    header('Location: .');
    exit;
}

// Validar y obtener datos del formulario
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$type = isset($_POST['type']) ? trim($_POST['type']) : '';
$ability = isset($_POST['ability']) ? trim($_POST['ability']) : '';
$hp = isset($_POST['hp']) ? (int)$_POST['hp'] : 0; // Asegúrate de que sea un número entero
$attack = isset($_POST['attack']) ? (int)$_POST['attack'] : 0; // Asegúrate de que sea un número entero
$defense = isset($_POST['defense']) ? (int)$_POST['defense'] : 0; // Asegúrate de que sea un número entero

// Validar que no haya campos vacíos
if (empty($name) || empty($type) || empty($ability) || $hp <= 0 || $attack <= 0 || $defense <= 0) {
    $_SESSION['old'] = $_POST;
    header('Location: create.php?op=add&result=0');
    exit;
}

// Preparar y ejecutar la consulta de inserción
$sql = 'INSERT INTO pokemon (name, type, ability, hp, attack, defense) VALUES (:name, :type, :ability, :hp, :attack, :defense)';
try {
    $sentence = $connection->prepare($sql);
    $sentence->bindParam(':name', $name);
    $sentence->bindParam(':type', $type);
    $sentence->bindParam(':ability', $ability);
    $sentence->bindParam(':hp', $hp);
    $sentence->bindParam(':attack', $attack);
    $sentence->bindParam(':defense', $defense);
    
    // Ejecutar la consulta
    if ($sentence->execute()) {
        header('Location: index.php?op=add&result=' . $connection->lastInsertId());
        exit;
    } else {
        header('Location: create.php?op=add&result=0');
        exit;
    }
} catch (PDOException $e) {
    header('Location: create.php?op=add&result=0');
    exit;
}

// Cerrar la conexión
$connection = null;
