<?php
header("Content-Type: application/json");
include("../conexao/conexao.php");

function listarUsuario($conn) {
    $stmt = $conn->prepare("
        select usuario.*, cargo.nome, cargo.nivel as nivel
        from usuario
        join cargo on usuario.id_cargo = cargo.id_cargo
    ");
    $stmt->execute() or die("SQL code execution failed: " . $stmt->error);
    $result = $stmt->get_result();
    $usuarios = [];
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
    return $usuarios;
}

echo json_encode(listarUsuario($conn))
?>