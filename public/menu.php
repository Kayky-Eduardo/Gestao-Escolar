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
    <?php if ((int) $_SESSION['nivel'] >= 4):?>
        <input type="submit" id="cadastro_profissionais" name="cadastro_profissionais" value="Cadastrar profissionais">
        <input type="submit" id="cadastro_sala" name="cadastro_sala" value="Cadastrar salas">
        <input type="submit" id="cadastro_alunos" name="cadastro_alunos" value="Cadastrar alunos">
        <input type="submit" id="sala_geral" name="sala_geral" value="Ver salas">
    <?php elseif ((int) $_SESSION['nivel'] >= 3):?>
        <input type="submit" id="sala_geral" name="sala_geral" value="Ver salas">
    <?php elseif ((int) $_SESSION['nivel'] == 2):?>
        <input type="submit" id="sala_responsavel" name="sala_responsavel" value="Ver salas que está responsável">
    <?php endif; ?>
    <ul id="listaTarefas"></ul>
    <script src="../js/script.js">

    </script>
</body>
</html>