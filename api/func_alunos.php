<?php
header("Content-Type: application/json");
include("../conexao/conexao.php");

function listarAluno($conn) {
    $stmt = $conn->prepare("select * from aluno");
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
    // permitir apenas campos seguros
    $permitidos = ['nome', 'genero', 'data_nascimento', 'id_sala', 'conta_ativa'];
    if (!in_array($campo, $permitidos)) {
        return false;
    }

    if (in_array($campo, ['id_sala', 'conta_ativa'], true)) {
        $sql = "UPDATE aluno SET $campo = ? WHERE matricula = ?";
        $stmt = $conn->prepare($sql);
        $v = (int)$valor;
        $stmt->bind_param("ii", $v, $matricula);
    } else {
        $sql = "UPDATE aluno SET $campo = ? WHERE matricula = ?";
        $stmt = $conn->prepare($sql);
        $v = (string)$valor;
        $stmt->bind_param("si", $v, $matricula);
    }

    return $stmt->execute();
}

function excluirAluno($conn, $matricula) {
    $stmt = $conn->prepare("delete from aluno where matricula = ?");
    $stmt->bind_param("i", $matricula);
    return $stmt->execute();
}

$acao = $_GET['acao'] ?? null;
$input = json_decode(file_get_contents('php://input'), true);
if ($acao === 'adicionar') {
    $possivel = adicionarAluno($conn, $input['nome'], $input['genero'], $input['data_nascimento'], (int)$input['id_sala']);
    echo json_encode(['success' => (bool)$possivel]);
    exit;
}

if ($acao === 'editar') {
    $possivel = editarCampo($conn, (int)$input['matricula'], $input['campo'], $input['valor']);
    echo json_encode(['success' => (bool)$possivel]);
    exit;
}

if ($acao === 'excluir') {
    $possivel = excluirAluno($conn, (int)$input['matricula']);
    echo json_encode(['success' => (bool)$possivel]);
    exit;
}

// padrão: listar
echo json_encode(listarAluno($conn));
?>