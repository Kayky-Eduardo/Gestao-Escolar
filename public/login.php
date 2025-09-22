<?php
include("../conexao/conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <form action="" method="POST">
        <label>E-mail:</label>
        <input type="text" name="email">

        <label>Password:</label>
        <input type="password" name="senha">

        <button type="submit">Login</button>
    </form>
</body>
</html>
<?php
if (isset($_POST['email'], $_POST['senha'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $stmt = $conn->prepare("
        select usuario.*, cargo.nome, cargo.nivel as nivel
        from usuario
        join cargo on usuario.id_cargo = cargo.id_cargo
        where usuario.email = ? and usuario.senha = ?
    ");
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute() or die("SQL code execution failed: " . $stmt->error);
    $result = $stmt->get_result();
    if($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if(!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['nivel'] = $user['nivel'];
        $_SESSION['user'] = $user['id_user'];
        $_SESSION['name'] = $user['nome'];
        header("Location: menu.php");
    } else {
        Echo "Login failed! Please check if your email or password is correct!";
    }
}
?>