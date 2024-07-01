<?php
include_once './config/config.php';
include_once './classes/Usuario.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $usuario = new Usuario($db);
    $codigo = $usuario->gerarCodigoVerificacao($email);

    if ($codigo) {
        $mensagem = "Seu código de verificação é: $codigo. Por favor, anote o código e <a href='redefinir_senha.php'>clique aqui</a> para redefinir sua senha.";
    } else {
        $mensagem = 'E-mail não encontrado.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styleRec.css">
    
</head>

<body>
    <header>
        <a href="index.php">
            <h1>Freakygram</h1>
        </a>
    </header>
    <div class="container">
        <h1>Recuperar Senha</h1>
        <form method="POST">
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="botao">
                <input type="submit" value="Enviar">
            </div>
        </form>
        <p><?php echo $mensagem; ?></p>
        <div class="links">
            <a href="login.php">Voltar</a>
        </div>
    </div>
    <footer>
        &copy; Copyright Vitor Souza | 2024
    </footer>
</body>

</html>
<style></style>