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
    <h1>Salas</h1>
    <?php if ((int) $_SESSION['nivel'] >= 4):?>
        <nav>
            <ul>
                <li><a href="menu.php">Home</a></li>
                <li><a href="usuarios.php">Usuarios</a></li>
                <li><a href="salas.php">Salas</a></li>
                <li><a href="alunos.php">Alunos</a></li>
                <li><a href="sala_responsavel.php">Detalhes salas</a></li>
                <li><a href="logout.php">logout</a></li>
                <li>Usuario: <?php echo $_SESSION['nome'] ?></li>
            </ul>
        </nav>
    <?php endif; ?>
    <?php if ((int) $_SESSION['nivel'] == 2):?>
        <nav>
            <ul>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="sala_responsavel.php">Salas</a></li>
                <li><a href="logout.php">logout</a></li>
            </ul>
        </nav>
        <!-- Verificar a possibilidade de criar inputs em cada nota ao invés do modal -->
        <!-- <div>
            <dialog id="dialog-editar">                
                <label>Campo:</label>
                <input type="text" id="campo"><br><br>
                
                <label>Novo valor:</label>
                <input type="text" id="novo-valor"><br><br>
    
                <button id="salvar" onclick="closeModal(modalEditar)">Salvar</button>
                <button onclick="closeModal(modalEditar)">Fechar</button>
            </dialog>
        </div> -->
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID sala</th>
                <th>Nome</th>
                <th>capacidade</th>
                <th>Data de Criação</th>
            </tr>
        </thead>
        <tbody id="Resposta">
        </tbody>
        <script src="../front/sala_responsavel.js">

    </script>
</body>
</html>