<?php
session_start();
include_once './Config/Config.php';
include_once './Classes/Usuario.php';
include_once './Classes/Noticia.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

$usuario = new Usuario($db);
$noticia = new Noticia($db);

// Deletar usuário se o parâmetro "deletar" estiver presente na URL
if (isset($_GET['deletar'])) {
    $id = $_GET['deletar'];
    $usuario->deletar($id);
    header('Location: portal.php');
    exit();
}

// Deletar notícia se o formulário de deletar notícia foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletar_noticia_id'])) {
    $idnot = $_POST['deletar_noticia_id'];
    $noticia->deletar($idnot);
    header('Location: portal.php');
    exit();
}

$dados_usuario = $usuario->lerPorId($_SESSION['usuario_id']);
$nome_usuario = $dados_usuario['nome'];

$dados_usuarios = $usuario->ler(); // Lista de todos os usuários

$noticias = $noticia->lerTodas(); // Lista de todas as notícias

function saudacao()
{
    $hora = date('H');
    if ($hora >= 6 && $hora < 12) {
        return "Bom dia";
    } else if ($hora >= 12 && $hora < 18) {
        return "Boa tarde";
    } else {
        return "Boa noite";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="portal.css" />
    <title>Portal</title>
</head>

<body>
    <div class="container">
        <h1><?php echo saudacao() . ", " . $nome_usuario; ?>!</h1>
        <div class="links">
            <a href="registrar.php">Adicionar usuário</a>
            <a href="logout.php">Logout</a>
        </div>
        <br>

        <!-- Tabela para exibir os usuários -->
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
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nome']; ?></td>
                        <td><?php echo ($row['sexo'] === 'M') ? 'Masculino' : 'Feminino'; ?></td>
                        <td><?php echo $row['fone']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $row['id']; ?>">Editar</a>
                            <a href="portal.php?deletar=<?php echo $row['id']; ?>">Deletar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <br>

        <!-- Botão para adicionar notícia -->
        <a href="adicionar_noticia.php" class="btn-adicionar">Adicionar Notícia</a>

        <br><br>

        <!-- Div para exibir as notícias -->
        <div class="noticias">
            <h2>Notícias</h2>
            <?php while ($noticia = $noticias->fetch(PDO::FETCH_ASSOC)) : ?>
                <div class="noticia">
                    <h3><?php echo $noticia['titulo']; ?></h3>
                    <p><?php echo $noticia['noticia']; ?></p>
                    <p><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($noticia['data'])); ?></p>
                    <?php
                    // Busca o autor da notícia pelo ID do usuário
                    $autor = $usuario->lerPorId($noticia['idusu']);
                    if ($autor) {
                        echo "<p><strong>Autor:</strong> " . $autor['nome'] . "</p>";
                    }
                    ?>
                    <form action="portal.php" method="post">
                        <input type="hidden" name="deletar_noticia_id" value="<?php echo $noticia['idnot']; ?>">
                        <input type="submit" value="Deletar">
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>

</html>
