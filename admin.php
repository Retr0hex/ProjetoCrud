<?php
// admin.php

session_start();
include_once './Config/Config.php';
include_once './Classes/Usuario.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

$usuario = new Usuario($db);

$usuario_id = $_SESSION['usuario_id'];
if (!$usuario->isAdmin($usuario_id)) {
    header('Location: portal.php');
    exit();
}

if (isset($_POST['id'])) {
    $id_usuario_deletar = $_POST['id'];

    if ($id_usuario_deletar == $usuario_id) {
        header('Location: admin.php');
        exit();
    }

    $usuario->deletar($id_usuario_deletar);
    exit();
}

$dados_usuarios = $usuario->ler();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin.css" />

    <title>Administração de Usuários</title>

    <script>
        function confirmarExclusao(id) {
            if (confirm('Tem certeza que deseja deletar este usuário e todas as suas notícias?')) {
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
                        alert('Erro ao excluir usuário.');
                    }
                }).catch(error => {
                    console.error('Erro ao tentar excluir usuário:', error);
                });
            }
        }

        function editarUsuario(id) {
            window.location.href = 'editar_usuario.php?id=' + id;
        }
    </script>
</head>

<body>
<header class="header">
    <div class="header-content">
        <div class="left">
            <h1>Freakygram</h1>
        </div>
        <div class="right">
            <nav class="top-links">
                <?php if (isset($_SESSION['usuario_id'])) : ?>
                    <button onclick="location.href='portal.php'" class="nav-button hover-underline-animation">Página inicial</button>
                <?php endif; ?>
                <button onclick="location.href='logout.php'" class="nav-button hover-underline-animation">Logout</button>
            </nav>
        </div>
    </div>
</header>

    <div class="container">
        <h1>Administração de Usuários</h1>
        <div class="links">
            <a href="registraradm.php">Adicionar usuário</a>
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
                            <button onclick="confirmarExclusao(<?php echo $row['id']; ?>)">Deletar</button>
                        </td>
                        <td>
                            <button onclick="editarUsuario(<?php echo $row['id']; ?>)">Editar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>
    <footer>
        &copy; Copyright Vitor Souza | 2024
    </footer>
</body>

</html>
