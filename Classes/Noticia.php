<?php
// Noticia.php

class Noticia
{
    private $conn;
    private $table_name = "noticias"; // nome da tabela

    public function __construct($db){
        $this->conn = $db;
    }

    public function criar($idusu, $titulo, $noticia, $data = null){
        $data = $data ? $data : date('Y-m-d');
        $query = "INSERT INTO " . $this->table_name . " (idusu, data, titulo, noticia) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idusu, $data, $titulo, $noticia]);
        return $stmt;
    }

    public function lerTodas(){
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function lerPorId($idnot){
        $query = "SELECT * FROM " . $this->table_name . " WHERE idnot = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idnot]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($idnot, $titulo, $noticia){
        $query = "UPDATE " . $this->table_name . " SET titulo = ?, noticia = ? WHERE idnot = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$titulo, $noticia, $idnot]);
        return $stmt;
    }

    public function deletar($idnot){
        $query = "DELETE FROM " . $this->table_name . " WHERE idnot = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idnot]);
        return $stmt;
    }

    public function buscarPorTitulo($termo){
        $query = "SELECT * FROM " . $this->table_name . " WHERE titulo LIKE ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(["%$termo%"]);
        return $stmt;
    }

    public function lerTodasComAutor(){
        $query = "
            SELECT n.*, u.nome as nome_autor
            FROM " . $this->table_name . " n
            LEFT JOIN usuarios u ON n.idusu = u.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function lerPorIdUsuario($idusu){
        $query = "SELECT * FROM " . $this->table_name . " WHERE idusu = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idusu]);
        return $stmt;
    }

    public function lerTodasComAutorOrdenadoPorTitulo(){
        $query = "
            SELECT n.*, u.nome as nome_autor
            FROM " . $this->table_name . " n
            LEFT JOIN usuarios u ON n.idusu = u.id
            ORDER BY n.titulo ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function lerPorIdUsuarioOrdenadoPorTitulo($idusu){
        $query = "SELECT * FROM " . $this->table_name . " WHERE idusu = ? ORDER BY titulo ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idusu]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
