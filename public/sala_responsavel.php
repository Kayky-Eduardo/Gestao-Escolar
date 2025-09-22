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
    <h1>Sala que você está responsável</h1>
    <ul>
        <li><a href="menu.php">Menu</a></li>
        <li><a href="sala_responsavel.php">Salas</a></li>
    </ul>
    <input type="submit" id="sala_responsavel" name="sala_responsavel" value="Ver salas que está responsável">
    <ul id="listaResposta"></ul>
    <script src="../js/script.js">

    </script>
</body>
</html>