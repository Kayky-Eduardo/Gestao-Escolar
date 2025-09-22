<?php
session_start();
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
    <h1>Usuarios</h1>
    <ul>
        <li><a href="menu.php">Menu</a></li>
        <li><a href="usuarios.php">Usuarios</a></li>
        <li><a href="salas.php">Salas</a></li>
        <li><a href="alunos.php">Alunos</a></li>
    </ul>
    <?php if ((int) $_SESSION['nivel'] >= 4):?>
        <input type="submit" id="cadastro_profissionais" name="cadastro_profissionais" value="Cadastrar usuário">
    <?php endif; ?>
    <ul id="listaResposta"></ul>
    <script src="../front/script.js">
    </script>
</body>
</html>