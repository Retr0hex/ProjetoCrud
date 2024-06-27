<?php
session_start();
include_once './Config/Config.php';
include_once './Classes/Noticia.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

$noticia = new Noticia($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idusu = $_SESSION['usuario_id'];
    $titulo = $_POST['titulo'];
    $noticia_texto = $_POST['noticia'];

    // Adicionar a notícia ao banco de dados
    $result = $noticia->criar($idusu, $titulo, $noticia_texto);

    if ($result) {
        // Redirecionar para o portal após adicionar a notícia
        header('Location: portal_noticia.php');
        exit();
    } else {
        $erro = "Erro ao adicionar notícia. Por favor, tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="noticia.css">
    <title>Adicionar Notícia</title>
</head>

<body>
    <div class="container">
        <form method="POST" class="form-noticia">
            <h2>Adicionar Notícia</h2>
            <?php if (isset($erro)) : ?>
                <p class="erro"><?php echo $erro; ?></p>
            <?php endif; ?>
            <div class="input-group">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" placeholder="Título da Notícia" required>
            </div>
            <div class="input-group">
                <label for="noticia">Notícia:</label>
                <textarea id="noticia" name="noticia" rows="5" placeholder="Digite sua notícia..." required></textarea>
            </div>
            <button type="submit" class="btn-adicionar">Adicionar Notícia</button>
            <div class="options">
                <a href="portal.php" class="link-voltar">Voltar para o Portal</a>
            </div>
        </form>
    </div>
</body>

</html>
