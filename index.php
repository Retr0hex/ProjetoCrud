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
    <link rel="stylesheet" type="text/css" href="portal.css" />
    <title>Notícias</title>
</head>

<body>
    
    <div class="container">
       
        <button >
  <span onclick="location.href='login.php'"class="hover-underline-animation">Login</span>
  <svg
    id="arrow-horizontal"
    xmlns="http://www.w3.org/2000/svg"
    width="30"
    height="10"
    viewBox="0 0 46 16"
  >
    <path
      id="Path_10"
      data-name="Path 10"
      d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z"
      transform="translate(30)"
    ></path>
  </svg>
</button>
<button style="padding-left: 10px;">
    <span onclick="location.href='registrar.php'" class="hover-underline-animation">Cadastro</span>
    <svg
        id="arrow-horizontal"
        xmlns="http://www.w3.org/2000/svg"
        width="30"
        height="10"
        viewBox="0 0 46 16"
    >
        <path
        id="Path_10"
        data-name="Path 10"
        d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z"
        transform="translate(30)"
        ></path>
    </svg>
</button>
        
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
</body>

</html>













