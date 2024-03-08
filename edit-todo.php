<?php
$filename = __DIR__ . "/data/todos.json";// variable du fichier

$_GET = filter_input_array(INPUT_GET, FILTER_VALIDATE_INT); //filtrer le get
$id = $_GET['id'] ?? '';// recupere l'id sinon vide

if ($id) {
    $data = file_get_contents($filename);//recupere le contenu du fichier
    $todos = json_decode($data, true) ?? [];//decode le fichier
    if (count($todos)) {
        $todoIndex = array_search($id, array_column($todos, 'id'));
        $todos[$todoIndex]['done'] = !$todos[$todoIndex]['done'];
        file_put_contents($filename, json_encode($todos));
    }
}
header('Location: /');