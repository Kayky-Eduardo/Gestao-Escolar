<?php
// Configurações da conexão
$host = "localhost";    // Servidor do banco
$user = "root";         // Usuário (padrão do xampp)
$pass = "";             // Senha (vazia no xampp por padrão)
$db = "gestao_escolar_k";   // nome do banco

// cria a conexão
$conn = new mysqli ($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Como funciona o bind param para inserir

// $stmt = $conn->prepare("INSERT into itens (nome, descricao) values (?, ?)");
// $stmt->bind_param("ss", $nome, $descricao); s = string, i = integer e etc
// $stmt->execute();

// Para selecionar

// prepare => bind_param => execute => get_result => fetch_all;
// se for select sem where não precisa de prepare
// $id = 4;
// $stmt = $conn->prepare("SELECT * from itens where id > ?");
// $stmt->bind_param("i", $id);
// $result = $stmt->get_result();
// $data = $result->fetch_all();
// print_r($data);
