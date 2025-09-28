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
    <?php if ((int) $_SESSION['nivel'] >= 3):?>
        <nav>
            <ul>
                <li><a href="menu.php">Home</a></li>
                <li><a href="usuarios.php">Usuarios</a></li>
                <li><a href="salas.php">Salas</a></li>
                <li><a href="alunos.php">Alunos</a></li>
                <li><a href="sala_responsavel.php">Detalhes salas</a></li>
                <li><a href="logout.php">logout</a></li>
                <li id="dados-user" data-id-user="<?php echo $_SESSION['usuario'] ?>"><?php echo "<a> Usuario: " . $_SESSION['nome'] . "</a>" ?></li>
            </ul>
        </nav>
    <?php endif; ?>
    <?php if ((int) $_SESSION['nivel'] == 2):?>
        <nav>
            <ul>
                <li><a href="menu.php">Home</a></li>
                <li><a href="sala_responsavel.php">Salas</a></li>
                <li><a href="logout.php">logout</a></li>
                <li id="dados-user" data-id-user="<?php echo $_SESSION['usuario'] ?>"><?php echo "<a> Usuario: " . $_SESSION['nome'] . "</a>" ?></li>
            </ul>
        </nav>
    <?php endif; ?>
    <h1>Salas</h1>

    <table>
        <thead>
            <tr>
                <th>ID sala</th>
                <th>Nome</th>
                <th>capacidade</th>
                <th>Data de Criação</th>
                <th>responsavel ID</th>
                <th>responsável</th>
                <th>Alunos</th>
                <th>Disciplina</th>
            </tr>
        </thead>
        <tbody id="Resposta">
        </tbody>
    </table>
    <hr>
    <div id="detalhesContainer">
    </div>
    <script src="../front/sala_responsavel.js"></script>
</body>
</html>