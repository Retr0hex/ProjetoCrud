<?php
    session_start();
    include_once './Config/Config.php';
    include_once './Classes/Usuario.php';

    $usuario = new Usuario($db);

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        if(isset($_POST['login'])){
            // Processar login
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            if($dados_usuario = $usuario->login($email, $senha)){
                $_SESSION['usuario_id'] = $dados_usuario['id'];
                header('location:portal.php');
                exit();
            }else{
                $mensagem_erro = "Credenciais inválidas!";
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <title>Autenticação</title>
</head>
<body>
    <div class="container">
        <form method="POST">
            <h2>Login</h2>
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" placeholder="Email" name="email" required>
            </div>
            <div class="input-group">
                <label for="senha">Senha:</label>
                <input type="password" placeholder="Senha" name="senha" required>
            </div>
            <button type="submit" name="login">Login</button>
            <div class="options">
                <a href="./registrar.php">Registrar-se</a>
                <a href="./repass.php">Recuperar senha</a>
            </div>
        </form>
    </div>
    <footer>
        &copy; Copyright Vitor Souza | 2024
    </footer>
    <br>
</body>
</html>





