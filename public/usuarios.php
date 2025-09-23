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
        <li><a href="logout.php">logout</a></li>
    </ul>

    <?php if ((int) $_SESSION['nivel'] >= 4):?>
        <div>
            <button onclick="openModal()">Cadastrar usuario</button>
            <dialog id="dialog">
                <h3>Cadastro</h3>
                
                <label>Nome:</label>
                <input type="text" id="nome"><br><br>
                
                <label>Email:</label>
                <input type="text" id="email"><br><br>
                
                <label>Senha:</label>
                <input type="text" id="senha"><br><br>
                
                <label>ID cargo:</label>
                <input type="text" id="id_cargo"><br><br>
                <button id="enviar" onclick="closeModal()">Enviar</button>
                <button id="enviar" onclick="closeModal()">Fechar</button>
            </dialog>
        </div>
    <?php endif; ?>
    <!-- <ul id="listaResposta"></ul> Transformar em tabela  -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Cargo</th>
                <th>Nivel</th>
            </tr>
        </thead>
        <tbody id="Resposta">
        </tbody>
    </table>
    <script src="../front/usuarios.js">
    </script>
</body>
</html>