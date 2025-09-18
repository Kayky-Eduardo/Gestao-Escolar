<?php
header("Content-Type: application/json");
include("../conexao/conexao.php");

$dados = json_decode(file_get_contents("php://input"), true);

/*
Extrai o valore da variavel da array e força a conversão para inteiros,
evitando inserção de valores invalidos ou maliciosos.
*/
$id = (int)$dados["id"];

// extrai o novo titulo e faz a proteção contra sql injection
$titulo = $conn->real_escape_string($dados["titulo"]);

$sql = "UPDATE tarefas set titulo = '$titulo' where id = $id";

$conn->query($sql);

echo json_encode(["status"=>"ok"]);
?>