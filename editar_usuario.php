<?php
// editar_usuario.php

include_once './Config/Config.php';
include_once './Classes/Usuario.php';

// Verifica se o ID do usuário foi fornecido via GET
if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit();
}

// Obtém o ID do usuário da URL
$id_usuario = $_GET['id'];

// Inicializa o objeto usuário com a conexão do banco de dados
$usuario = new Usuario($db);

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $sexo = $_POST['sexo'];
    $fone = $_POST['fone'];
    $email = $_POST['email'];
    $isAdmin = isset($_POST['admin']) ? 1 : 0; // Verifica se o checkbox 'admin' está marcado

    // Executa a atualização no banco de dados
    $usuario->atualizar($id_usuario, $nome, $sexo, $fone, $email, $isAdmin);

    // Redireciona de volta para admin.php após a atualização
    header('Location: admin.php');
    exit();
}

// Busca os dados do usuário pelo ID
$dados_usuario = $usuario->lerPorId($id_usuario);

// Verifica se o usuário existe
if (!$dados_usuario) {
    header('Location: admin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="registrar.css" />
    <title>Editar Usuário</title>
</head>

<body>
    <div class="container">
        <h1>Editar Usuário</h1>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $dados_usuario['id']; ?>">
            <div class="input-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" value="<?php echo $dados_usuario['nome']; ?>" required>
            </div>
            <div class="input-group">
                <label>Sexo:</label>
                <div class="radio-group">
                    <label for="masculino">
                        <input type="radio" id="masculino" name="sexo" value="M" <?php echo ($dados_usuario['sexo'] === 'M') ? 'checked' : ''; ?> required> Masculino
                    </label>
                    <label for="feminino">
                        <input type="radio" id="feminino" name="sexo" value="F" <?php echo ($dados_usuario['sexo'] === 'F') ? 'checked' : ''; ?> required> Feminino
                    </label>
                </div>
            </div>
            <div class="input-group">
                <label for="fone">Fone:</label>
                <input type="text" name="fone" value="<?php echo $dados_usuario['fone']; ?>" required>
            </div>
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo $dados_usuario['email']; ?>" required>
            </div>
            <div class="input-group">
                <label>
                    <input type="checkbox" name="admin" <?php echo ($dados_usuario['admin'] == 1) ? 'checked' : ''; ?>> Administrador
                </label>
            </div>
            <input type="submit" value="Atualizar">
        </form>
    </div>
</body>

</html>
