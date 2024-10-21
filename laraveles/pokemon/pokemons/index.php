<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

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

// Obtener lista de Pokémon
$sql = 'SELECT * FROM pokemon';
$statement = $connection->prepare($sql);

try {
    $statement->execute();
    $pokemons = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    header('Location: ./');
    exit;
}

$connection = null;
?>

<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>dwes - Lista de Pokémon</title>
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
                    <h4 class="display-4">Lista de Pokémon</h4>
                </div>
            </div>
            <div class="container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Ability</th>
                            <th>HP</th>
                            <th>Attack</th>
                            <th>Defense</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pokemons as $pokemon): ?>
                        <tr>
                            <td><?= htmlspecialchars($pokemon['id']) ?></td>
                            <td><?= htmlspecialchars($pokemon['name']) ?></td>
                            <td><?= htmlspecialchars($pokemon['type']) ?></td>
                            <td><?= htmlspecialchars($pokemon['ability']) ?></td>
                            <td><?= htmlspecialchars($pokemon['hp']) ?></td>
                            <td><?= htmlspecialchars($pokemon['attack']) ?></td>
                            <td><?= htmlspecialchars($pokemon['defense']) ?></td>
                            <td>
                                <a href="edit.php?id=<?= $pokemon['id'] ?>" class="btn ">Edit</a>
                                <a href="destroy.php?id=<?= $pokemon['id'] ?>" class="btn ">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div>
                    <a href="create.php" class="btn btn-success">Add New Pokémon</a>
                </div>
            </div>
        </main>
        <footer class="container">
            <p>&copy; IZV 2024</p>
        </footer>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>
