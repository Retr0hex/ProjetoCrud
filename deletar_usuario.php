<?php
// deletar_usuario.php

session_start();
include_once './Config/Config.php';
include_once './Classes/Usuario.php';
include_once './Classes/Noticia.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

$usuario = new Usuario($db);

if (isset($_POST['id'])) {
    $id_usuario = $_POST['id'];

    // Deleta o usuário e suas notícias
    if ($usuario->deletar($id_usuario)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao deletar usuário.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID do usuário não fornecido.']);
}
?>
