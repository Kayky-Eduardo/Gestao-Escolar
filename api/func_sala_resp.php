<?php
header("Content-Type: application/json");
include("../conexao/conexao.php");

function listarSalas($conn) {
    $stmt = $conn->prepare("
        select sala.*, usuario.nome_usuario
        from sala
        join usuario on usuario.id_user = id_responsavel;
    ");
    $stmt->execute() or die("SQL code execution failed: " . $stmt->error);
    $result = $stmt->get_result();
    $salas = [];
    while ($row = $result->fetch_assoc()) {
        $salas[] = $row;
    }
    return $salas;
}

// listar todos os alunos presente nesta sala
function listarAlunosSala($conn, $id_sala) {
    $stmt = $conn->prepare("
        SELECT a.matricula, a.nome
        FROM aluno a
        WHERE a.id_sala = ?;
    ");
    $stmt->bind_param("i", $id_sala);
    $stmt->execute() or die("SQL code execution failed: " . $stmt->error);
    $result = $stmt->get_result();
    $alunos_SE = [];
    while ($row = $result->fetch_assoc()) {
        $alunos_SE[] = $row;
    }
    return $alunos_SE;
}

// Listar todas as disciplinas dentro de uma sala
function listarDisciplinaSala($conn, $id_sala) {
    $stmt = $conn->prepare("
        select d.id_disciplina, d.nome as disciplina
        from sala_disciplina sd
        join disciplina d on d.id_disciplina = sd.id_disciplina
        where sd.id_sala = ?;
    ");
    $stmt->bind_param("i", $id_sala);
    $stmt->execute() or die("SQL code execution failed: " . $stmt->error);
    $result = $stmt->get_result();
    $salaDisciplinas = [];
    while ($row = $result->fetch_assoc()){
        $salaDisciplinas[] = $row;
    }
    return $salaDisciplinas;
}

// No seu arquivo func_sala_resp.php, adicione a função
function listarAlunosNotasDisciplina($conn, $id_sala, $id_disciplina) {
    // Sua query SQL para buscar:
    // aluno.nome, aluno.matricula, nota.b1, nota.b2, nota.b3, nota.b4
    // WHERE aluno.id_sala = ? AND nota.id_disciplina = ?
    // Retorna o array.
}


$acao = $_GET['acao'] ?? null;
$input = json_decode(file_get_contents('php://input'), true);

// E adicione a chamada no final
if ($acao == "alunosNotasDisciplina") {
    echo json_encode(listarAlunosNotasDisciplina($conn, $input['id_sala'], $input['id_disciplina']));
    exit;
}
if ($acao == "alunoSala") {
    echo json_encode(listarAlunosSala($conn, $input['id_sala']));
    exit;

}

if ($acao == "disciplinaSala") {
    echo json_encode(listarDisciplinaSala($conn, $input['id_sala']));
    exit;

}

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