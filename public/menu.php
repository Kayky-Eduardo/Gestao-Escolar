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
    <h1>Home</h1>
    <?php if ((int) $_SESSION['nivel'] >= 3):?>
        <ul>
            <li><a href="menu.php">Home</a></li>
            <li><a href="usuarios.php">Usuarios</a></li>
            <li><a href="salas.php">Salas</a></li>
            <li><a href="alunos.php">Alunos</a></li>
            <li><a href="sala_responsavel.php">Detalhes salas</a></li>
            <li><a href="logout.php">logout</a></li>
            <li>Usuario: <?php echo $_SESSION['nome'] ?></li>
        </ul>
    <?php elseif ((int) $_SESSION['nivel'] == 2):?>
        <ul>
            <li><a href="menu.php">Home</a></li>
            <li><a href="sala_responsavel.php">Salas</a></li>
        </ul>
    <?php endif; ?>
</body>
</html>