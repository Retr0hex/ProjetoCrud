<?php
session_start();
include_once './Config/Config.php'; 
include_once './Classes/Usuario.php';
include_once './Classes/Noticia.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

$usuario = new Usuario($db);
$noticia = new Noticia($db);

$dados_usuario = $usuario->lerPorId($_SESSION['usuario_id']);
$nome_usuario = $dados_usuario['nome'];
$admin = $usuario->isAdmin($_SESSION['usuario_id']);

$noticias = $noticia->lerTodasComAutor(); 

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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletar_noticia_id'])) {
    $idnot = $_POST['deletar_noticia_id'];
    $noticia->deletar($idnot);
    header('Location: portal.php?deletado=true');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="portal_noticia.css">
    <title>Portal</title>

    <script>
        let dataFiltrada = false;

        function filtrarNoticias(ordem) {
            const noticias = document.querySelectorAll('.noticia');

            if (ordem === 'titulo') {
                const noticiasArray = Array.from(noticias);
                noticiasArray.sort((a, b) => {
                    const tituloA = a.querySelector('h3').textContent.toUpperCase();
                    const tituloB = b.querySelector('h3').textContent.toUpperCase();
                    if (tituloA < tituloB) return -1;
                    if (tituloA > tituloB) return 1;
                    return 0;
                });
                noticiasArray.forEach(noticia => document.querySelector('.noticias').appendChild(noticia));
                dataFiltrada = false;
            } else if (ordem === 'data') {
                if (!dataFiltrada) {
                    const noticiasArray = Array.from(noticias);
                    noticiasArray.sort((a, b) => {
                        const dataA = new Date(a.querySelector('strong').nextSibling.nodeValue.trim().split(': ')[1]);
                        const dataB = new Date(b.querySelector('strong').nextSibling.nodeValue.trim().split(': ')[1]);
                        return dataB - dataA;
                    });
                    noticiasArray.forEach(noticia => document.querySelector('.noticias').appendChild(noticia));
                    dataFiltrada = true;
                } else {
                    const noticiasArray = Array.from(noticias).reverse();
                    noticiasArray.forEach(noticia => document.querySelector('.noticias').appendChild(noticia));
                    dataFiltrada = false;
                }
            }
        }
    </script>
</head>

<body>
    <div class="container">
        <h1><?php echo saudacao() . ", " . $nome_usuario; ?>!</h1>
        <div class="links">
            <?php if ($admin) : ?>
                <a href="admin.php">Administrar usuários</a>
            <?php endif; ?>
            <a href="adicionar_noticia.php">Adicionar Notícia</a>
            <a href="logout.php">Logout</a>
        </div>
        <br>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Pesquisar...">
            <button onclick="filtrarNoticias('')">Pesquisar</button>
            <button onclick="filtrarNoticias('titulo')">Ordem alfabética</button>
            <button onclick="filtrarNoticias('data')">Data</button>
        </div>

        <div class="noticias">
            <h2>Notícias</h2>
            <?php if ($noticias->rowCount() == 0) : ?>
                <div class="no-news">
                    <img src="https://cdn1.iconfinder.com/data/icons/facebook-ui/48/additional_icons-28-256.png" height="80px" width="80px">
                    <p>Nenhuma notícia por enquanto</p>
                </div>
            <?php else : ?>
                <?php while ($noticia = $noticias->fetch(PDO::FETCH_ASSOC)) : ?>
                    <div class="noticia">
                        <h3><?php echo $noticia['titulo']; ?></h3>
                        <p><?php echo $noticia['noticia']; ?></p>
                        <p><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($noticia['data'])); ?></p>
                        <?php
                       
                        $autor = $usuario->lerPorId($noticia['idusu']);
                        if ($autor) {
                            echo "<p><strong>Autor:</strong> " . $autor['nome'] . "</p>";
                        }
                        ?>





                        <form id="deleteNewsForm<?php echo $noticia['idnot']; ?>" action="portal.php" method="post">
                            <input type="hidden" name="deletar_noticia_id" value="<?php echo $noticia['idnot']; ?>">
                            <?php if ($admin || $noticia['idusu'] == $_SESSION['usuario_id']) : ?>
                                <a href="javascript:void(0)" onclick="if (confirm('Tem certeza que deseja deletar esta notícia?')) { document.getElementById('deleteNewsForm<?php echo $noticia['idnot']; ?>').submit(); }">Deletar</a>
                            <?php endif; ?>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
