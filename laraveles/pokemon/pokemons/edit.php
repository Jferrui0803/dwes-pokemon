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

// Verificar si el parámetro 'id' está presente y es numérico
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
} else {
    header('Location: ./?op=editpokemon&result=noid');
    exit;
}

// Preparar la consulta para obtener el Pokémon
$sql = 'SELECT * FROM pokemon WHERE id = :id';
$sentence = $connection->prepare($sql);
$sentence->bindValue(':id', $id, PDO::PARAM_INT);

try {
    // Ejecutar la consulta
    $sentence->execute();
    $row = $sentence->fetch(PDO::FETCH_ASSOC); // Obtener como array asociativo
} catch (PDOException $e) {
    header('Location: ./');
    exit;
}

// Verificar si el Pokémon existe
if ($row == null) {
    header('Location: ./');
    exit;
}

// Cerrar la conexión
$connection = null;

// Variables para mantener los datos del formulario anterior
$name = isset($_SESSION['old']['name']) ? $_SESSION['old']['name'] : $row['name'];
$type = isset($_SESSION['old']['type']) ? $_SESSION['old']['type'] : $row['type'];
$ability = isset($_SESSION['old']['ability']) ? $_SESSION['old']['ability'] : $row['ability'];
$hp = isset($_SESSION['old']['hp']) ? $_SESSION['old']['hp'] : $row['hp'];
$attack = isset($_SESSION['old']['attack']) ? $_SESSION['old']['attack'] : $row['attack'];
$defense = isset($_SESSION['old']['defense']) ? $_SESSION['old']['defense'] : $row['defense'];
unset($_SESSION['old']);
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Pokemon</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
                    <h4 class="display-4">Edit Pokemon</h4>
                </div>
            </div>
            <div class="container">
                <form action="update.php" method="post">
                    <div class="form-group">
                        <label for="name">Pokemon Name</label>
                        <input value="<?= htmlspecialchars($name) ?>" required type="text" class="form-control" id="name" name="name" placeholder="Pokemon Name">
                    </div>
                    <div class="form-group">
                        <label for="type">Pokemon Type</label>
                        <input value="<?= htmlspecialchars($type) ?>" required type="text" class="form-control" id="type" name="type" placeholder="Pokemon Type">
                    </div>
                    <div class="form-group">
                        <label for="ability">Pokemon Ability</label>
                        <input value="<?= htmlspecialchars($ability) ?>" required type="text" class="form-control" id="ability" name="ability" placeholder="Pokemon Ability">
                    </div>
                    <div class="form-group">
                        <label for="hp">Pokemon HP</label>
                        <input value="<?= htmlspecialchars($hp) ?>" required type="number" class="form-control" id="hp" name="hp" placeholder="Pokemon HP">
                    </div>
                    <div class="form-group">
                        <label for="attack">Pokemon Attack</label>
                        <input value="<?= htmlspecialchars($attack) ?>" required type="number" class="form-control" id="attack" name="attack" placeholder="Pokemon Attack">
                    </div>
                    <div class="form-group">
                        <label for="defense">Pokemon Defense</label>
                        <input value="<?= htmlspecialchars($defense) ?>" required type="number" class="form-control" id="defense" name="defense" placeholder="Pokemon Defense">
                    </div>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>" />
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
                <hr>
            </div>
        </main>
        <footer class="container">
            <p>&copy; IZV 2024</p>
        </footer>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>
