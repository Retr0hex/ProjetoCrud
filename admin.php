<?php
session_start();
include_once './Config/Config.php';
include_once './Classes/Usuario.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

$usuario = new Usuario($db);

// Verifica se o usuário logado é administrador
$usuario_id = $_SESSION['usuario_id'];
if (!$usuario->isAdmin($usuario_id)) {
    header('Location: portal.php'); // Redireciona para o portal se não for admin
    exit();
}

// Verifica se foi solicitada a exclusão de um usuário
if (isset($_POST['id'])) {
    $id_usuario_deletar = $_POST['id'];

    // Impede que um admin exclua a si mesmo
    if ($id_usuario_deletar == $usuario_id) {
        header('Location: admin.php');
        exit();
    }

    // Deleta o usuário
    $usuario->deletar($id_usuario_deletar);
    exit(); // Termina o script após deletar o usuário
}

// Obtém todos os usuários para exibição na tabela
$dados_usuarios = $usuario->ler();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="portal.css" />
    <title>Administração de Usuários</title>

    <script>
        function confirmarExclusao(id) {
            if (confirm('Tem certeza que deseja deletar este usuário?')) {
                fetch('deletar_usuario.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + id,
                }).then(response => {
                    if (response.ok) {
                        document.getElementById('user_' + id).remove();
                    } else {
                        alert('Erro ao excluir usuário.' +);
                    }
                }).catch(error => {
                    console.error('Erro ao tentar excluir usuário:', error);
                });
            }
        }
    </script>
</head>

<body>
    <div class="container">
        <h1>Administração de Usuários</h1>
        <div class="links">
            <a href="registrar.php">Adicionar usuário</a>
            <a href="logout.php">Logout</a>
        </div>
        <br>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Sexo</th>
                    <th>Fone</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $dados_usuarios->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr id="user_<?php echo $row['id']; ?>">
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nome']; ?></td>
                        <td><?php echo ($row['sexo'] === 'M') ? 'Masculino' : 'Feminino'; ?></td>
                        <td><?php echo $row['fone']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <?php if ($row['id'] != $usuario_id) : ?>
                                <a href="javascript:void(0);" onclick="confirmarExclusao(<?php echo $row['id']; ?>)">Deletar</a>
                            <?php else : ?>
                                <span>Não permitido</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>
</body>

</html>
