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

function adicionarSala($conn, $nome_sala, $capacidade, $id_responsavel) {
    $stmt= $conn->prepare("
        insert into sala (nome_sala, capacidade, id_responsavel)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("sii", $nome_sala, $capacidade, $id_responsavel);
    if($stmt->execute()) {
        $id_sala = $conn->insert_id;        
        $disciplinas = ['Matematica', 'Geografia', 'Historia', 'Portugues'];
        $id_disciplinas = [];
        $stmtDisciplina = $conn->prepare("select id_disciplina from disciplina where nome = ?");
        foreach($disciplinas as $disciplina) {
            $stmtDisciplina->bind_param("s", $disciplina);
            $stmtDisciplina->execute();
            $result = $stmtDisciplina->get_result();
            if ($row = $result->fetch_assoc()) {
                $id_disciplinas[] = $row['id_disciplina'];
            }
        }
        $stmtInsert = $conn->prepare("
        insert into sala_disciplina (id_sala, id_disciplina, id_professor)
        values (?, ?, ?)
        ");
        foreach ($id_disciplinas as $id_disciplina) {
            $stmtInsert->bind_param("iii", $id_sala, $id_disciplina, $id_responsavel);
            $stmtInsert->execute();
        }
        return true;
    }
}

function excluirSala($conn, $id_sala) {
    $stmt = $conn->prepare("delete from sala where id_sala = ?");
    $stmt->bind_param("i", $id_sala);
    return $stmt->execute();
}

function editarCampo($conn, $id_sala, $campo, $valor) {
    $permitidos = ['nome', 'nome_sala', 'capacidade', 'responsavel', 'id_responsavel'];
    if (!in_array($campo, $permitidos)) {
        return false;
    }
    if (in_array($campo, ['nome_sala']) || in_array($campo, ['nome'])) {
        $sql = "update sala set nome_sala = ? where id_sala = ?";
        $stmt = $conn->prepare($sql);
        $v = (string)$valor;
        $stmt->bind_param("si", $v, $id_sala);
    } else {
        $sql = "update sala set $campo = ? where id_sala = ?";
        $stmt = $conn->prepare($sql);
        $v = (int)$valor;
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