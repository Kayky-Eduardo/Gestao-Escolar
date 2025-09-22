<?php
session_start();
include("../conexao/conexao.php");
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
        <li><a href="menu.php">Menu</a></li>
        <li><a href="usuarios.php">Usuarios</a></li>
        <li><a href="salas.php">Salas</a></li>
        <li><a href="alunos.php">Alunos</a></li>
    </ul>
    <?php if ((int) $_SESSION['nivel'] >= 4):?>
        <input type="submit" id="cadastro_alunos" name="cadastro_alunos" value="Cadastrar alunos">
    <?php endif; ?>
    <ul id="listaResposta"></ul>
    <script src="../front/aluno.js"></script>
</body>
</html>