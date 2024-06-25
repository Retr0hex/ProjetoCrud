<?php
session_start();
include_once './Config/Config.php'; // Arquivo de configuração do banco de dados
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
$admin = $usuario->isAdmin($_SESSION['usuario_id']); // Verifica se é administrador

$noticias = $noticia->lerTodas();

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

// Processamento para deletar notícia
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletar_noticia_id'])) {
    $idnot = $_POST['deletar_noticia_id'];
    $noticia->deletar($idnot);
    header('Location: portal.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="portal.css" />
    <title>Portal</title>

    <script>
        function openModal(id, type) {
            const modal = document.getElementById("confirmModal");
            modal.style.display = "block";
            modal.classList.add("bounceIn");
            document.getElementById("confirmDelete").onclick = function () {
                if (type === 'user') {
                    window.location.href = `portal.php?deletar=${id}`;
                } else if (type === 'news') {
                    document.getElementById(`deleteNewsForm${id}`).submit();
                }
            };
        }

        function closeModal() {
            const modal = document.getElementById("confirmModal");
            modal.classList.remove("bounceIn");
            modal.classList.add("bounceOut");
            setTimeout(function () {
                modal.style.display = "none";
                modal.classList.remove("bounceOut");
            }, 500); // Aguarda o final da animação (0.5s)
        }

        window.onclick = function(event) {
            const modal = document.getElementById("confirmModal");
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</head>

<body>
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirmação</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p>Deseja mesmo excluir?</p>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeModal()">Não</button>
                <button id="confirmDelete" class="btn-confirm">Sim</button>
            </div>
        </div>
    </div>

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
                        // Busca o autor da notícia pelo ID do usuário
                        $autor = $usuario->lerPorId($noticia['idusu']);
                        if ($autor) {
                            echo "<p><strong>Autor:</strong> " . $autor['nome'] . "</p>";
                        }
                        ?>
                        <form id="deleteNewsForm<?php echo $noticia['idnot']; ?>" action="portal.php" method="post">
                            <input type="hidden" name="deletar_noticia_id" value="<?php echo $noticia['idnot']; ?>">
                            <?php if ($admin || $noticia['idusu'] == $_SESSION['usuario_id']) : ?>
                                <a href="javascript:void(0)" onclick="openModal(<?php echo $noticia['idnot']; ?>, 'news')">Deletar</a>
                            <?php endif; ?>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
