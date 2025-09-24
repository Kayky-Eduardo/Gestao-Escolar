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
    <title>Alunos </title>
    <link rel="stylesheet" href="../css/estilo.css">

</head>
<body>
    <h1>Alunos</h1>
    <?php if ((int) $_SESSION['nivel'] >= 4):?>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
            </tr>
        </thead>
        <tbody id="Resposta">
        </tbody>
    </table>
    <script src="../front/exibir_alunos.js"></script>
</body>
</html>