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
    <h1>Alunos</h1>
    <ul>
        <li><a href="menu.php">Home</a></li>
        <li><a href="usuarios.php">Usuarios</a></li>
        <li><a href="salas.php">Salas</a></li>
        <li><a href="alunos.php">Alunos</a></li>
        <li><a href="sala_responsavel.php">Detalhes salas</a></li>
        <li><a href="logout.php">logout</a></li>
    </ul>
    <?php if ((int) $_SESSION['nivel'] >= 4):?>
        <div>
            <button onclick="openModal(modalAdd)">Cadastrar Alunos</button>
            <dialog id="dialog-adicionar">
                <h3>Cadastro</h3>
                <label>ID Sala:</label>
                <input type="text" id="id_sala"><br><br>
                
                <label>nome:</label>
                <input type="text" id="nome"><br><br>
                
                <label>genero:</label>
                <input type="text" id="genero"><br><br>
                
                <label>Data de nacimento(ex: aaaa-mm-dd):</label><br>
                <input type="text" id="data_nascimento"><br><br>
                

                <button id="enviar" onclick="closeModal(modalAdd)">Enviar</button>
                <button id="enviar" onclick="closeModal(modalAdd)">Fechar</button>
            </dialog>
        </div>
        <div>
            <dialog id="dialog-editar">                
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
                <th>Matrícula</th>
                <th>Nome</th>
                <th>genero</th>
                <th>Data de Nascimento</th>
                <th>ID Sala</th>
            </tr>
        </thead>
        <tbody id="Resposta">
        </tbody>
    </table>
    <script src="../front/aluno.js"></script>
</body>
</html>