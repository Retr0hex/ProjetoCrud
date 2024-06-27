<?php
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

    public function lerTodasComAutor(){
        $query = "
            SELECT n.*, u.nome as nome_autor
            FROM " . $this->table_name . " n
            LEFT JOIN usuarios u ON n.idusu = u.id
            ORDER BY n.data DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para ler todas as notícias ordenadas por título ou data
    public function lerTodasOrdenadas($ordenacao){
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY ";
        switch ($ordenacao) {
            case 'titulo':
                $query .= "titulo ASC";
                break;
            case 'data':
                $query .= "data DESC";
                break;
            default:
                $query .= "data DESC"; // Ordenação padrão por data DESC
                break;
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para buscar notícias por título
    public function buscarPorTitulo($termo){
        $query = "SELECT * FROM " . $this->table_name . " WHERE titulo LIKE ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(["%$termo%"]);
        return $stmt;
    }
}
?>
