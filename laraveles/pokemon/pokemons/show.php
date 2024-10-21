<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $connection = new \PDO(
      'mysql:host=localhost;dbname=pokemons',
      'root',
      'FERNANDO',
      array(
        PDO::ATTR_PERSISTENT => true,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8')
    );
} catch(PDOException $e) {
    header('Location:..');
    exit;
}
if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $url = '.?op=showpokemon&result=noid';
    header('Location: ' . $url);
    exit;
}
$sql = 'SELECT * FROM pokemon WHERE id = :id';
$sentence = $connection->prepare($sql);
$parameters = ['id' => $id];
foreach($parameters as $nombreParametro => $valorParametro) {
    $sentence->bindValue($nombreParametro, $valorParametro);
}
try {
    $sentence->execute();
} catch(PDOException $e) {
    $url = '.?op=showpokemon&result=nosql';
    header('Location: ' . $url);
    exit;
}

if(!$fila = $sentence->fetch(PDO::FETCH_ASSOC)) {
    $url = '.?op=showpokemon&result=nofetch';
    header('Location: ' . $url);
    exit;
}

$connection = null;
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>View Pokemon</title>
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
                    <h4 class="display-4">Pokemon Details</h4>
                </div>
            </div>
            <div class="container">
                <div>
                    <div class="form-group">
                        <strong>Pokemon ID:</strong> <?= htmlspecialchars($fila['id']) ?>
                    </div>
                    <div class="form-group">
                        <strong>Pokemon Name:</strong> <?= htmlspecialchars($fila['name']) ?>
                    </div>
                    <div class="form-group">
                        <strong>Pokemon Type:</strong> <?= htmlspecialchars($fila['type']) ?>
                    </div>
                    <div class="form-group">
                        <strong>Pokemon Ability:</strong> <?= htmlspecialchars($fila['ability']) ?>
                    </div>
                    <div class="form-group">
                        <strong>Pokemon HP:</strong> <?= htmlspecialchars($fila['hp']) ?>
                    </div>
                    <div class="form-group">
                        <strong>Pokemon Attack:</strong> <?= htmlspecialchars($fila['attack']) ?>
                    </div>
                    <div class="form-group">
                        <strong>Pokemon Defense:</strong> <?= htmlspecialchars($fila['defense']) ?>
                    </div>
                    <div class="form-group">
                        <a href="./" class="btn btn-secondary">Back</a>
                    </div>
                </div>
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
