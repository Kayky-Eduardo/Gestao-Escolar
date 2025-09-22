<?php
header("Content-Type: application/json");
include("../conexao/conexao.php");

function listarAluno($conn) {
    $stmt = $conn->prepare("
        select * from aluno
    ");
    $stmt->execute() or die("SQL code execution failed: " . $stmt->error);
    $result = $stmt->get_result();
    $alunos = [];
    while ($row = $result->fetch_assoc()) {
        $alunos[] = $row;
    }
    return $alunos;
}

function adicionarAluno($conn, $nome, $genero, $data_nascimento, $id_sala) {
    $stmt= $conn->prepare("
        INSERT INTO aluno (nome, genero, data_nascimento, id_sala)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("sssi", $nome, $genero, $data_nascimento, $id_sala);
    return $stmt->execute();
}

function editarCampo($conn, $matricula, $campo, $valor) {
    $camposPermitidos = ["nome", "genero", "data_nascimento", "conta_ativa", "id_sala"];
    if (!in_array($campo, $camposPermitidos)) {
        throw new Exception("Campo inválido");
    }
    $sql = "UPDATE aluno SET $campo = ? WHERE matricula = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $valor, $matricula);
    return $stmt->execute();
}


function excluirAluno($conn, $matricula) {
    $stmt = $conn->prepare("delete from aluno where matricula = ?");
    $stmt->bind_param("i", $matricula);
    return $stmt->execute();
}

echo json_encode(listarAluno($conn));

?>