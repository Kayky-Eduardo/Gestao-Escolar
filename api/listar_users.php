<?php
// define que a resposta do servidor será enviada no formato json, para que o javascript
// saiba interpretar os dados.
header("Content-Type: application/json");
include("../conexao/conexao.php");


// $sql = "SELECT * FROM usuario ORDER BY id_user DESC";

$sql = "
select usuario.*, cargo.nome, cargo.nivel as nivel
from usuario
join cargo on usuario.id_cargo = cargo.id_cargo
";
$result = $conn->query($sql);

$tarefas = [];

// percorre cada linha do resultado da consulta e transforma em um array associativo
while ($row = $result->fetch_assoc()) {
    $tarefas[] = $row;
}
// converte o array em uma string json e envia a resposta ao cliente(navegador)
echo json_encode($tarefas);
?>