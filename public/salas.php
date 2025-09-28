<?php
session_start();
if (!$_SESSION['nivel']) {
    header("Location: logout.php");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="../css/estilo.css">

</head>
<body>
    <nav>
        <ul>
            <li><a href="menu.php">Home</a></li>
            <li><a href="usuarios.php">Usuarios</a></li>
            <li><a href="salas.php">Salas</a></li>
            <li><a href="alunos.php">Alunos</a></li>
            <li><a href="sala_responsavel.php">Detalhes salas</a></li>
            <li><a href="logout.php">logout</a></li>
            <li><?php echo "<a> Usuario: " . $_SESSION['nome'] . "</a>"?></li>
        </ul>
    </nav>
    <h1>Salas</h1>
    <?php if ((int) $_SESSION['nivel'] >= 4):?>
        <div>
            <div class="btn-container">
                <button onclick="openModal(modalAdd)">Cadastrar Sala</button>
            </div>
            <dialog id="dialog-adicionar">
                <h2>Cadastro</h2>
                
                <label>ID responsavel:</label>
                <input type="text" id="id_responsavel"><br><br>
                
                <label>nome sala:</label>
                <input type="text" id="nome_sala"><br><br>
                
                <label>capacidade:</label>
                <input type="text" id="capacidade"><br><br>
                
                <button id="enviar" onclick="closeModal(modalAdd)">Enviar</button>
                <button id="enviar" onclick="closeModal(modalAdd)">Fechar</button>
            </dialog>
        </div>
        <div>
            <dialog id="dialog-editar">
                <h2>Edição</h2>                
         
                <label>Campo:</label>
                <input type="text" id="campo"><br><br>
                
                <label>Novo valor:</label>
                <input type="text" id="novo-valor"><br><br>
    
                <button id="salvar" onclick="closeModal(modalEditar)">Salvar</button>
                <button onclick="closeModal(modalEditar)">Fechar</button>
            </dialog>
        </div>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>ID sala</th>
                <th>Nome</th>
                <th>capacidade</th>
                <th>Data de Criação</th>
                <th>ID responsavel</th>
                <th>responsável</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="Resposta">
        </tbody>
    </table>
    <script src="../front/sala.js"></script>
</body>
</html>