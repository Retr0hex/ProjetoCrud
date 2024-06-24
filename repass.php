<?php
session_start();
include_once './Config/Config.php'; // Supondo que este arquivo contém a configuração do banco de dados
include_once './Classes/Usuario.php'; // Supondo que este arquivo contém a classe Usuario

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = $_POST['email'];
    $usuario = new Usuario($db); // Supondo que $db é a conexão PDO com o banco de dados
    $dados_usuario = $usuario->buscarPorEmail($email);

    if ($dados_usuario) {
        $token = bin2hex(random_bytes(50)); // gera um token de 100 caracteres

        // definir a data de expiração para 1 hora a partir de agora
        $expire_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // salvar o token no banco de dados junto com o email e a data de expiração
        $sql = "INSERT INTO reset_password (email, token, expire_at) VALUES (:email, :token, :expire_at)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expire_at', $expire_at);
        $stmt->execute();

        // Envio do email
        $envio_email = enviarEmailRecuperacaoSenha($email, $token);

        // Verifica se o email foi enviado com sucesso
        if ($envio_email) {
            $_SESSION['success_message'] = "Um email foi enviado para você com instruções para redefinir sua senha.";
        } else {
            $_SESSION['error_message'] = "Houve um problema ao enviar o email. Por favor, tente novamente mais tarde.";
        }
    } else {
        $_SESSION['error_message'] = "Email não encontrado. Verifique se o email está correto.";
    }

    // Redireciona de volta para a página de recuperação de senha
    header('Location: repass.php');
    exit();
}

function enviarEmailRecuperacaoSenha($email, $token) {
    $assunto = 'Recuperação de Senha';
    $mensagem = 'Olá! Para redefinir sua senha, clique no link abaixo:';
    $mensagem .= "\n\n";
    $mensagem .= 'http://seusite.com/nova-senha.php?token=' . urlencode($token); // URL para redefinição de senha

    // cabeçalho
    $headers = 'From: vitoryungscarlxrd@gmail.com' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();

    // envia
    return mail($email, $assunto, $mensagem, $headers);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="repass.css" />
    <title>Recuperar senha</title>
</head>
<body>
    <div class="container">
        <h1>Recuperar Senha</h1>
        <form method="POST">
            <div class="input-group">
                <label for="email">Digite seu email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" name="submit">Enviar</button>
        </form>
    </div>
</body>
</html>
