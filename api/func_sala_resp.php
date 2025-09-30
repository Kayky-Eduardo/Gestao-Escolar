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

function listarSalasResponsavel($conn, $id_user) {
    $stmt = $conn->prepare("
    select sala.*, usuario.nome_usuario
    from sala 
    join usuario on usuario.id_user = sala.id_responsavel
    where sala.id_responsavel = ?;
    ");
    $stmt->bind_param("i", $id_user);
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
        from aluno a
        where a.id_sala = ?;
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
    $stmt = $conn->prepare("
    select 
        a.matricula,
        a.nome as nome_aluno,
        max(case when n.bimestre = 1 then n.valor end) as bimestre_1,
        max(case when n.bimestre = 2 then n.valor end) as bimestre_2,
        max(case when n.bimestre = 3 then n.valor end) as bimestre_3,
        max(case when n.bimestre = 4 then n.valor end) as bimestre_4
    from 
        aluno a
    left join 
        nota n ON n.matricula = a.matricula AND n.id_disciplina = ?
    where 
        a.id_sala = ?
    group by 
        a.matricula, a.nome
    order by 
        a.nome
    ");
    $stmt->bind_param("ii", $id_disciplina, $id_sala);
    $stmt->execute() or die("Execução do código sql falhou: " . $stmt->error);
    $result = $stmt->get_result();
    $alunosNotas = [];
    while ($row = $result->fetch_assoc()) {
        $alunosNotas[] = $row;
    }
    return $alunosNotas;
}

function salvarNotas($conn, $matricula, $id_sala, $id_disciplina, $lista_valores) {
    $bimestres = [1, 2, 3, 4];
    
    // Converte os valores para FLOAT e os armazena em um array.
    $valores = array_map(function($valor) {
        // Trata a string vazia ou null como PHP null, e vírgula como ponto
        $valor = trim(str_replace(',', '.', $valor));
        return (empty($valor) && $valor !== '0') ? null : (float)$valor;
    }, $lista_valores);

    $conn->begin_transaction(); // Inicia a transação
    
    try {
        // Prepara os statements fora do loop para melhor performance
        $stmt_update = $conn->prepare("
            UPDATE nota SET valor = ?, data_registro = NOW()
            WHERE matricula = ? AND id_disciplina = ? AND bimestre = ?
        ");

        $stmt_insert = $conn->prepare("
            INSERT INTO nota (matricula, id_sala, id_disciplina, bimestre, valor)
            VALUES (?, ?, ?, ?, ?)
        ");
        
        for($i = 0; $i < 4; $i++) {
            $bimestre = $bimestres[$i];
            $valor = $valores[$i];
            
            // --- Lógica de UPDATE ---
            $stmt_update->bind_param("siii", $valor, $matricula, $id_disciplina, $bimestre);
            $stmt_update->execute();
            
            // Lança exceção em caso de erro no SQL
            if ($stmt_update->error) {
                 throw new \Exception("Erro no UPDATE SQL: " . $stmt_update->error);
            }
            
            if ($stmt_update->affected_rows === 0) {
                // --- Lógica de INSERT ---
                if ($valor !== null) {
                    $stmt_insert->bind_param("iiiis", $matricula, $id_sala, $id_disciplina, $bimestre, $valor);
                    $stmt_insert->execute();
                    
                    // Lança exceção em caso de erro no SQL
                    if ($stmt_insert->error) {
                        throw new \Exception("Erro no INSERT SQL: " . $stmt_insert->error);
                    }
                }
            }
        }
        
        // Finaliza os statements
        $stmt_update->close();
        $stmt_insert->close();
        
        $conn->commit(); // Confirma todas as operações
        return true;
        
    } catch (\Exception $e) { // <-- A alteração está aqui
        $conn->rollback(); // Reverte se houver erro
        // Loga o erro
        error_log("Erro ao salvar notas (Transação): " . $e->getMessage());
        return false;
    }
}

$acao = $_GET['acao'] ?? null;
$input = json_decode(file_get_contents('php://input'), true);

// E adicione a chamada no final
if ($acao == "alunosNotasDisciplina") {
    echo json_encode(listarAlunosNotasDisciplina($conn, $input['id_sala'], $input['id_disciplina']));
    exit;
}

if($acao == "salvarNotas") {
    $success = salvarNotas($conn, $input['matricula'], $input['id_sala'], $input['id_disciplina'], $input['lista_valores']);
    
    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'erro' => 'Falha ao processar as notas no banco de dados.']);
    }
    exit;
}


if ($acao == "listarSalasResponsavel") { 
    if (isset($input['id_responsavel'])) {
        $id_responsavel = (int)$input['id_responsavel'];
        echo json_encode(listarSalasResponsavel($conn, $id_responsavel));
    } else {
        // Erro se o ID não for enviado no JSON
        echo json_encode(["erro" => "ID do responsável ausente no corpo da requisição POST."]);
    }
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
// echo json_encode(listarSalas($conn));
?>