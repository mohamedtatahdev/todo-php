<?php
const ERROR_REQUIRED = 'Veuillez renseigner une todo'; //cree une variable pour gérer l'erreur
const ERROR_TOO_SHORT = 'Veuilez entrez au moins 5 caractères';
$filename = __DIR__ . "/data/todos.json"; // recupere le fichier json
$error = ''; // cree une variable de chaine de caractere vide pour l'erreur
$todo = ''; // pour eviter  l'erreur
$todos = []; //initialise le tableau todo

if (file_exists($filename)) {
  $data = file_get_contents($filename); // recupere ce qu'il y a à l'interieur du fichier
  $todos = json_decode($data, true) ?? []; // decode le json en tableau associatif sinon tableau nul
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // si le serveur a une requete de type post
  $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS); // filtre la todo
  $todo = $_POST['todo'] ?? ''; // entre crocher le nom de l'input concerner

  if (!$todo) {
    $error = ERROR_REQUIRED; // gere l'erreur si c'est vide
  } else if (mb_strlen($todo) < 5) {
    $error = ERROR_TOO_SHORT; // gere l'erreur si ya moin de 5 caractere
  }

  if (!$error) { // si il n'y a pas d'erreur
    $todos = [...$todos, [
      'name' => $todo,
      'done' => false,
      'id' => time()
    ]];
    file_put_contents($filename, json_encode($todos)); //encode le tableau todo
    header('Location: /');// evite la validation a l'actualisation

  }
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="public/css/style.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
  <title>Todo</title>
</head>

<body>
  <div class="container">

    <?php require_once 'includes/header.php' ?>

    <div class="content">
      <div class="todo-container">
        <h1>Ma Todo</h1>
        <form class="todo-form" action="/" method="post">
          <input name="todo" type="text" value="<?= $todo ?>">
          <button class="btn btn-primary">Ajouter</button>
        </form>
        <?php if ($error) : ?>
          <p class="text-danger"><?= $error ?></p> <!-- affiche l'erreur -->
        <?php endif; ?>
        <ul class="todo-list">
          <?php foreach ($todos as $t) : ?>
            <li class="todo-item <?= $t['done'] ? 'low-opacity' : '' ?>">
  <span class="todo-name"><?= $t['name'] ?></span>
  <a href="/edit-todo.php?id=<?= $t['id'] ?>">
    <button class="btn btn-primary btn-small"><?= $t['done'] ? 'Annuler' : 'Valider' ?></button>
  </a>
  <a href="/remove-todo.php?id=<?= $t['id'] ?>">
<button class="btn btn-danger btn-small">Supprimer</button>
</a>
</li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <?php require_once 'includes/footer.php' ?>
  </div>
</body>

</html>