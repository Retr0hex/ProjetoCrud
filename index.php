<?php
session_start();
include_once './Config/Config.php';
include_once './Classes/Usuario.php';
include_once './Classes/Noticia.php';

$noticia = new Noticia($db);
$usuario = new Usuario($db);

$noticias = $noticia->lerTodasComAutor();
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="portal_noticia.css" />
    <title>Notícias</title>
</head>

<body>
    <header class="header">
        <div class="header-content">
            <h1>Freakygram</h1>
            <nav class="top-links">
                <button onclick="location.href='login.php'" class="nav-button hover-underline-animation">Login</button>
                <button onclick="location.href='registrar.php'" class="nav-button hover-underline-animation">Cadastro</button>
            </nav>
        </div>
    </header>
    <div class="container">
        <h1>Notícias do grande mundo</h1>
        <div class="noticias">
            <?php if ($noticias->rowCount() == 0) : ?>
                <div class="no-news">
                    <p>Nenhuma notícia disponível no momento.</p>
                </div>
            <?php else : ?>
                <?php while ($noticia = $noticias->fetch(PDO::FETCH_ASSOC)) : ?>
                    <div class="noticia">
                        <h3><?php echo $noticia['titulo']; ?></h3>
                        <p><?php echo $noticia['noticia']; ?></p>
                        <p><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($noticia['data'])); ?></p>
                        <?php if ($noticia['nome_autor']) : ?>
                            <p><strong>Autor:</strong> <?php echo $noticia['nome_autor']; ?></p>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Exemplo de script JavaScript para interatividade na página (opcional)
        document.addEventListener('DOMContentLoaded', function() {
            // Exemplo de ação ao clicar em um elemento
            const noticias = document.querySelectorAll('.noticia');
            noticias.forEach(noticia => {
                noticia.addEventListener('click', function() {
                    alert('Clicou na notícia: ' + this.querySelector('h3').textContent);
                });
            });
        });
    </script>
</body>

</html>

