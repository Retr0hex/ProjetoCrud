<?php
session_start(); // Inicia a sessão (se ainda não estiver iniciada)
include_once './config/config.php';
include_once './classes/Usuario.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'];
    $nova_senha = $_POST['nova_senha'];
    $usuario = new Usuario($db);

    if ($usuario->redefinirSenha($codigo, $nova_senha)) {
        $_SESSION['senha_alterada'] = true; // Define uma variável de sessão para indicar sucesso
        header('Location: login.php'); // Redireciona para login.php
        exit;
    } else {
        $mensagem = 'Código de verificação inválido.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
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
        <h1>Redefinir Senha</h1>
        <form method="POST">
            <label for="codigo">Código de Verificação:</label>
            <input type="text" name="codigo" placeholder="Seu código aqui" required><br><br>
            <label for="nova_senha">Nova Senha:</label>
            <input type="password" name="nova_senha" id="nova_senha" required>
            <div class="botao">
                <input type="submit" value="Redefinir Senha">
            </div>
        </form>
    </div>
    <p><?php echo $mensagem; ?></p>
    <footer>
        Copyright Vitor Souza | 2024
    </footer>

    <script>
        // JavaScript para exibir alerta quando a senha for alterada com sucesso
        <?php if (isset($_SESSION['senha_alterada']) && $_SESSION['senha_alterada']) : ?>
        alert("Senha alterada com sucesso!");
        <?php unset($_SESSION['senha_alterada']); ?> // Limpa a variável de sessão após o alerta
        <?php endif; ?>
    </script>
    <footer>
        &copy; Copyright Vitor Souza | 2024
    </footer>
    <br>
</body>

</html>
