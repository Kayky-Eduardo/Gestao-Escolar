<?php
// Configurações da conexão
$host = "localhost";    // Servidor do banco
$user = "root";         // Usuário (padrão do xampp)
$pass = "";             // Senha (vazia no xampp por padrão)
$db = "gestao_escolar_k";   // nome do banco

// cria a conexão
$conn = new mysql($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}