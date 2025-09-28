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
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
</head>
<body>
    <?php if ((int) $_SESSION['nivel'] >= 3):?>
    <h1 class="home-titulo">Home</h1>

    <ul class="home-menu">
        <li><a href="menu.php">Home</a></li>
        <li><a href="usuarios.php">Usuários</a></li>
        <li><a href="salas.php">Salas</a></li>
        <li><a href="alunos.php">Alunos</a></li>
        <li><a href="sala_responsavel.php">Detalhes Salas</a></li>
        <li><a href="logout.php">Logout</a></li>
        <li class="usuario-logado"><?php echo "<a>Usuário: " . $_SESSION['nome'] . "</a>"?></li>
    </ul>
    <?php elseif ((int) $_SESSION['nivel'] == 2):?>
        <h1 class="home-titulo">Home</h1>
        <ul class="home-menu">
            <li><a href="menu.php">Home</a></li>
            <li><a href="sala_responsavel.php">Detalhes Salas</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li class="usuario-logado"><?php echo "<a>Usuário: " . $_SESSION['nome'] . "</a>"?></li>
        </ul>
    <?php endif; ?>

</body>
</html>
