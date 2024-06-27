<?php
session_start();
include_once './Config/Config.php'; // Arquivo de configuração do banco de dados
include_once './Classes/Noticia.php';

$noticia = new Noticia($db);

$termo_pesquisa = isset($_GET['pesquisar']) ? $_GET['pesquisar'] : '';

if (!empty($termo_pesquisa)) {
    $noticias = $noticia->buscarPorTitulo($termo_pesquisa);
} else {
    $noticias = $noticia->lerTodas();
}

if ($noticias->rowCount() > 0) {
    while ($noticia = $noticias->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="noticia">';
        echo '<h3>' . $noticia['titulo'] . '</h3>';
        echo '<p>' . $noticia['noticia'] . '</p>';
        echo '<p><strong>Data:</strong> ' . date('d/m/Y', strtotime($noticia['data'])) . '</p>';
        // Exibir autor, se necessário
        echo '</div>';
    }
} else {
    echo '<div class="no-news">';
    echo '<img src="https://cdn1.iconfinder.com/data/icons/facebook-ui/48/additional_icons-28-256.png" height="80px" width="80px">';
    echo '<p>Nenhuma notícia encontrada</p>';
    echo '</div>';
}
?>
