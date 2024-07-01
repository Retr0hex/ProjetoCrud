<?php
include_once './Config/Config.php';
include_once './Classes/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Usuario($db);
    $nome = $_POST['nome'];
    $sexo = $_POST['sexo'];
    $fone = $_POST['fone'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $isAdmin = isset($_POST['admin']) ? 1 : 0; // Verifica se o checkbox 'admin' está marcado

    $usuario->criar($nome, $sexo, $fone, $email, $senha, $isAdmin);

    // Redirecionamento para login.php após o registro
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="registrar.css" />
    <title>Adicionar Usuário</title>
</head>

<body>
    <div class="container">
        <h1>Adicionar Usuário</h1>
        <form method="POST">
            <div class="input-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" placeholder="Nome" required>
            </div>
            <div class="input-group">
                <label>Sexo:</label>
                <div class="radio-group">
                    <label for="masculino">
                        <input type="radio" id="masculino" name="sexo" value="M" required> Masculino
                    </label>
                    <label for="feminino">
                        <input type="radio" id="feminino" name="sexo" value="F" required> Feminino
                    </label>
                </div>
            </div>
            <div class="input-group">
                <label for="fone">Fone:</label>
                <input type="text" name="fone" placeholder="Fone" required>
            </div>
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <label for="senha">Senha:</label>
                <input type="password" name="senha" placeholder="Senha" required>
            </div>
          
            <input type="submit" value="Adicionar">
        </form>
    </div>
    <footer>
        &copy; Copyright Vitor Souza | 2024
    </footer>
    <br>
</body>

</html>
