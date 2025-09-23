<?php
header("Content-Type: application/json");
include("../conexao/conexao.php");


// todos os alunos da sala

// SELECT a.matricula, a.nome
// FROM aluno a
// WHERE a.id_sala = 1;

// Todas disciplinas em uma sala

// SELECT d.id_disciplina, d.nome AS disciplina
// FROM sala_disciplina sd
// JOIN disciplina d ON d.id_disciplina = sd.id_disciplina
// WHERE sd.id_sala = 1;


function listarSalas($conn) {
    $stmt = $conn->prepare("
        select sala.*, usuario.nome_usuario
        from sala
        join usuario on usuario.id_user = id_responsavel;
    ");
    $stmt->execute() or die("SQL code execution failed: " . $stmt->error);
    $result = $stmt->get_result();
    $usuarios = [];
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
    return $usuarios;
}

function adicionarSala($conn, $nome_sala, $capacidade, $id_responsavel) {
    $stmt= $conn->prepare("
        insert into sala (nome_sala, capacidade, id_responsavel)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("sii", $nome_sala, $capacidade, $id_responsavel);
    return $stmt->execute();
}

function excluirSala($conn, $id_sala) {
    $stmt = $conn->prepare("delete from sala where $id_sala = ?");
    $stmt->bind_param("i", $id_sala);
    return $stmt->execute();
}

function editarCampo($conn, $id_sala, $campo, $valor) {
    // permitir apenas campos seguros
    $permitidos = ['nome_sala', 'capacidade', 'responsavel', 'id_responsavel'];
    if (!in_array($campo, $permitidos)) {
        return false;
    }
    if (in_array($campo, ['nome_sala'])) {
        $sql = "update sala set $campo = ? where id_sala = ?";
        $stmt = $conn->prepare($sql);
        $v = (int)$valor;
        $stmt->bind_param("si", $v, $id_sala);
    } else {
        $sql = "update sala set $campo = ? where id_sala = ?";
        $stmt = $conn->prepare($sql);
        $v = (string)$valor;
        $stmt->bind_param("ii", $v, $id_sala);
    }

    return $stmt->execute();
}


$acao = $_GET['acao'] ?? null;
$input = json_decode(file_get_contents('php://input'), true);
if ($acao === 'adicionar') {
    $possivel = adicionarSala($conn, $input['nome_sala'], $input['capacidade'], (int)$input['id_responsavel']);
    echo json_encode(['success' => (bool)$possivel]);
    exit;
}

if ($acao === 'editar') {
    $possivel = editarCampo($conn, (int)$input['id_sala'], $input['campo'], $input['valor']);
    echo json_encode(['success' => (bool)$possivel]);
    exit;
}

if ($acao === 'excluir') {
    $possivel = excluirSala($conn, (int)$input['id_sala']);
    echo json_encode(['success' => (bool)$possivel]);
    exit;
}

// padrão: listar
echo json_encode(listarSalas($conn));
?>