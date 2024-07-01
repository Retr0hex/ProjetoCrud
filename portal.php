<?php
session_start();
include_once './Config/Config.php'; // Arquivo de configuração do banco de dados
include_once './Classes/Usuario.php';
include_once './Classes/Noticia.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

// Inicializa objetos de Noticia e Usuario
$noticia = new Noticia($db);
$usuario = new Usuario($db);

// Obtém o ID do usuário da sessão
$usuario_id = $_SESSION['usuario_id'];

// Verifica se o usuário é administrador
$admin = $usuario->isAdmin($usuario_id);

// Processamento para deletar notícia
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletar_noticia_id'])) {
    $idnot = $_POST['deletar_noticia_id'];
    $noticia->deletar($idnot);
    header('Location: portal.php');
    exit();
}

// Processamento para buscar notícias
if (isset($_GET['pesquisar'])) {
    $termo = $_GET['pesquisar'];
    $noticias = $noticia->buscarPorTitulo($termo);
} else {
    // Se não está buscando, exibe todas as notícias do usuário ou todas se for admin
    if ($admin) {
        $noticias = $noticia->lerTodasComAutor();
    } else {
        $noticias = $noticia->lerPorIdUsuario($usuario_id);
    }
}

// Processamento para ordenar notícias
$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : 'data'; // Padrão para ordenar por data se não houver especificação

switch ($ordem) {
    case 'titulo':
        if ($admin) {
            $noticias = $noticia->lerTodasComAutorOrdenadoPorTitulo();
        } else {
            $noticias = $noticia->lerPorIdUsuarioOrdenadoPorTitulo($usuario_id);
        }
        break;
    case 'data':
    default:
        if ($admin) {
            $noticias = $noticia->lerTodasComAutor();
        } else {
            $noticias = $noticia->lerPorIdUsuario($usuario_id);
        }
        break;
}

// Função para saudação baseada na hora do dia
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
    <link rel="stylesheet" type="text/css" href="portal_noticia.css" />
    <title>Portal de Notícias</title>
</head>

<body>
    <header class="header">
        <div class="header-content">
            <h1>Freakygram</h1>
            <nav class="top-links">
                <?php if (isset($_SESSION['usuario_id'])) : ?>
                    <?php if ($admin) : ?>
                        <button onclick="location.href='admin.php'" class="nav-button hover-underline-animation">Administrar Usuários</button>
                    <?php endif; ?>
                    <button onclick="location.href='adicionar_noticia.php'" class="nav-button hover-underline-animation">Adicionar Notícia</button>
                    <button onclick="location.href='logout.php'" class="nav-button hover-underline-animation">Logout</button>
                <?php else : ?>
                    <button onclick="location.href='login.php'" class="nav-button hover-underline-animation">Login</button>
                    <button onclick="location.href='registrar.php'" class="nav-button hover-underline-animation">Cadastro</button>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1><?php echo saudacao() . ", " . $usuario->lerPorId($usuario_id)['nome']; ?>!</h1>

        <!-- Barra de pesquisa -->
        <div class="search-bar">
            <form method="GET" action="portal.php" style="display: inline-block;">
                <input type="text" name="pesquisar" id="pesquisar" placeholder="Pesquisar...">
                <button type="submit">Pesquisar</button>
            </form>
            <?php if ($admin) : ?>
                <div style="display: inline-block; margin-left: 10px;">
                    <button onclick="location.href='portal.php?ordem=titulo'" style="margin-right: 5px;">Ordenar por Título</button>
                    <button onclick="location.href='portal.php?ordem=data'" style="margin-left: 5px;">Ordenar por Data</button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Lista de notícias -->
        <div class="noticias">
            <h2>Notícias</h2>
            <?php if ($noticias && $noticias->rowCount() > 0) : ?>
                <?php foreach ($noticias as $noticia) : ?>
                    <div class="noticia">
                        <h3><?php echo $noticia['titulo']; ?></h3>
                        <p><?php echo $noticia['noticia']; ?></p>
                        <p><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($noticia['data'])); ?></p>
                        <?php if ($admin) : ?>
                            <?php
                            // Busca o autor da notícia pelo ID do usuário
                            $autor = $usuario->lerPorId($noticia['idusu']);
                            if ($autor) {
                                echo "<p><strong>Autor:</strong> " . $autor['nome'] . "</p>";
                            }
                            ?>
                        <?php endif; ?>
                        <?php if ($admin || $noticia['idusu'] == $usuario_id) : ?>
                            <form id="deleteForm<?php echo $noticia['idnot']; ?>" method="post" action="portal.php" style="margin-top: 10px;">
                                <input type="hidden" name="deletar_noticia_id" value="<?php echo $noticia['idnot']; ?>">
                                <button type="button" onclick="confirmarExclusao(<?php echo $noticia['idnot']; ?>)">Deletar Notícia</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Nenhuma notícia encontrada.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        &copy; Copyright Vitor Souza | 2024
    </footer>

    <script>
        // Função JavaScript para confirmar exclusão de notícia
        function confirmarExclusao(id) {
            if (confirm("Tem certeza que deseja excluir esta notícia?")) {
                document.getElementById('deleteForm' + id).submit();
            }
        }
    </script>
</body>

</html>
<style></style>