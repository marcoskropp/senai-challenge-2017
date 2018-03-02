<?php

require_once '../app/Config.inc.php';
$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//limpando possiveis tags
$dados = array_map("strip_tags", $post);

//criando uma variavel para utilizar como jSon
$jSon = array();

if ($dados['action'] == "create") {
    $create = new Create;
    $dados = [
        'lat' => $dados['latitude'],
        'lng' => $dados['longitude']
    ];
    $create->exeCreate("maps", $dados);
} elseif ($dados['action'] == "read") {
    $read = new Read;
    $read->fullRead("SELECT * FROM maps ORDER BY id DESC LIMIT 1");
    $resultado = $read->getResult();
}
$jSon['lat'] = $resultado[0]['lat'];
$jSon['lng'] = $resultado[0]['lng'];

echo json_encode($jSon);
return;
