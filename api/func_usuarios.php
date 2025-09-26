<?php
header("Content-Type: application/json");
include("../conexao/conexao.php");

function listarUsuario($conn) {
    $stmt = $conn->prepare("
        select usuario.*, cargo.nome_cargo, cargo.nivel as nivel
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

function adicionarUsuario($conn, $nome, $email, $senha, $id_cargo) {
    $stmt= $conn->prepare("
        insert into usuario (nome_usuario, email, senha, id_cargo)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("sssi", $nome, $email, $senha, $id_cargo);
    return $stmt->execute();
}


// function editarCampo($conn, $id_user, $campo, $valor) {
//     // permitir apenas campos seguros
//     $permitidos = ['nome', 'nome_usuario', 'email', 'senha', 'id_cargo', 'nivel'];
//     if (!in_array($campo, $permitidos)) {
//         return false;
//     }

//     if (in_array($campo, ['id_cargo']) || in_array($campo, ['nivel'])) {
//         $sql = "update usuario set id_cargo = ? where id_user = ?";
//         $stmt = $conn->prepare($sql);
//         $v = (int)$valor;
//         $stmt->bind_param("ii", $v, $id_user);
//     } elseif (in_array($campo, ['nome', 'nome_usuario'])) {
//         $stmt = $conn->prepare("update usuario set nome_usuario = ? where id_user = ?");
//         $v = (string)$valor;
//         $stmt->bind_param("si", $v, $id_user);
//     } else {
//         $sql = "update usuario set $campo = ? where id_user = ?";
//         $stmt = $conn->prepare($sql);
//         $v = (string)$valor;
//         $stmt->bind_param("si", $v, $id_user);
//     }

//     return $stmt->execute();
// }

function editarCampo($conn, $id_user, $campo, $valor) {
    // permitir apenas campos seguros
    $permitidos = ['nome', 'nome_usuario', 'email', 'senha', 'id_cargo', 'nivel'];
    if (!in_array($campo, $permitidos)) {
        return false;
    }
    
    // Lógica para 'nivel'
    if ($campo === 'nivel') {
        // Encontra o id_cargo correspondente ao novo nível
        $sql = "SELECT id_cargo FROM cargo WHERE nivel = ?";
        $stmt_nivel = $conn->prepare($sql);
        $v_nivel = (int)$valor;
        $stmt_nivel->bind_param("i", $v_nivel);
        $stmt_nivel->execute();
        $resultado = $stmt_nivel->get_result();
        
        // Se um cargo com esse nível existir, atualiza o usuário
        if ($resultado->num_rows > 0) {
            $dados_cargo = $resultado->fetch_assoc();
            $novo_id_cargo = $dados_cargo['id_cargo'];
            
            $sql_atualiza_usuario = "UPDATE usuario SET id_cargo = ? WHERE id_user = ?";
            $stmt_atualiza_usuario = $conn->prepare($sql_atualiza_usuario);
            $stmt_atualiza_usuario->bind_param("ii", $novo_id_cargo, $id_user);
            return $stmt_atualiza_usuario->execute();
        } else {
            // Nível não encontrado, a atualização falha
            return false;
        }
    }
    
    // Lógica para 'id_cargo'
    if ($campo === 'id_cargo') {
        $sql = "UPDATE usuario SET id_cargo = ? WHERE id_user = ?";
        $stmt = $conn->prepare($sql);
        $v = (int)$valor;
        $stmt->bind_param("ii", $v, $id_user);
        return $stmt->execute();
    }
    if (in_array($campo, ['nome', 'nome_usuario'])) {
        $sql = "UPDATE usuario SET nome_usuario = ? WHERE id_user = ?";
        $stmt = $conn->prepare($sql);
        $v = (string)$valor;
        $stmt->bind_param("si", $v, $id_user);
        return $stmt->execute();    
    } else {
        // Lógica para os outros campos (nome, nome_usuario, etc.)
        $sql = "UPDATE usuario SET $campo = ? WHERE id_user = ?";
        $stmt = $conn->prepare($sql);
        $v = (string)$valor;
        $stmt->bind_param("si", $v, $id_user);
        return $stmt->execute();
    }
    
}

function excluirUsuario($conn, $id_user) {
    $stmt = $conn->prepare("delete from usuario where id_user = ?");
    $stmt->bind_param("i", $id_user);
    return $stmt->execute();
}

$acao = $_GET['acao'] ?? null;
$input = json_decode(file_get_contents('php://input'), true);
if ($acao === 'adicionar') {
    $possivel = adicionarUsuario($conn, $input['nome'], $input['email'], $input['senha'], (int)$input['id_cargo']);
    echo json_encode(['success' => (bool)$possivel]);
    exit;
}

if ($acao === 'editar') {
    $possivel = editarCampo($conn, (int)$input['id_user'], $input['campo'], $input['valor']);
    echo json_encode(['success' => (bool)$possivel]);
    exit;
}

if ($acao === 'excluir') {
    $possivel = excluirUsuario($conn, (int)$input['id_user']);
    echo json_encode(['success' => (bool)$possivel]);
    exit;
}

// padrão: listar
echo json_encode(listarUsuario($conn));
?>