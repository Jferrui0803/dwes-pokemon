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

// Manejo del formulario de creación de Pokémon
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? '';
    $ability = $_POST['ability'] ?? '';
    $hp = $_POST['hp'] ?? 0;
    $attack = $_POST['attack'] ?? 0;
    $defense = $_POST['defense'] ?? 0;

    // Validar datos
    if ($name && $type && $ability && is_numeric($hp) && is_numeric($attack) && is_numeric($defense)) {
        // Insertar en la base de datos
        $sql = 'INSERT INTO pokemon (name, type, ability, hp, attack, defense) VALUES (:name, :type, :ability, :hp, :attack, :defense)';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':name', $name);
        $sentence->bindValue(':type', $type);
        $sentence->bindValue(':ability', $ability);
        $sentence->bindValue(':hp', $hp);
        $sentence->bindValue(':attack', $attack);
        $sentence->bindValue(':defense', $defense);

        try {
            $sentence->execute();
            $url = './?op=createpokemon&result=success';
            header('Location: ' . $url);
            exit;
        } catch (PDOException $e) {
            $url = './?op=createpokemon&result=error';
            header('Location: ' . $url);
            exit;
        }
    } else {
        $url = './?op=createpokemon&result=validation';
        header('Location: ' . $url);
        exit;
    }
}

// Cerrar la conexión
$connection = null;
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create Pokemon</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="..">dwes</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="..">Home</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="./">Pokemons</a>
                </li>
            </ul>
        </div>
    </nav>
    <main role="main">
        <div class="jumbotron">
            <div class="container">
                <h4 class="display-4">Create New Pokemon</h4>
            </div>
        </div>
        <div class="container">
            <form action="create.php" method="post">
                <div class="form-group">
                    <label for="name">Pokemon Name</label>
                    <input required type="text" class="form-control" id="name" name="name" placeholder="Pokemon Name">
                </div>
                <div class="form-group">
                    <label for="type">Pokemon Type</label>
                    <input required type="text" class="form-control" id="type" name="type" placeholder="Pokemon Type">
                </div>
                <div class="form-group">
                    <label for="ability">Pokemon Ability</label>
                    <input required type="text" class="form-control" id="ability" name="ability" placeholder="Pokemon Ability">
                </div>
                <div class="form-group">
                    <label for="hp">Pokemon HP</label>
                    <input required type="number" class="form-control" id="hp" name="hp" placeholder="Pokemon HP" min="0">
                </div>
                <div class="form-group">
                    <label for="attack">Pokemon Attack</label>
                    <input required type="number" class="form-control" id="attack" name="attack" placeholder="Pokemon Attack" min="0">
                </div>
                <div class="form-group">
                    <label for="defense">Pokemon Defense</label>
                    <input required type="number" class="form-control" id="defense" name="defense" placeholder="Pokemon Defense" min="0">
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </main>
    <footer class="container">
        <p>&copy; IZV 2024</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
